<?php

namespace AppAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use AppAdmin\View\Helper\RowsPerPage;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Doctrine\ORM\EntityManager;
use Application\Entity\Order as OrderEntity;
use Application\Repository\Order as OrderRepository;
use AppAdmin\Form\Order\Filter as FilterForm;
use AppAdmin\Form\Order\Edit as EditForm;
use AppAdmin\Form\Sorter as SorterForm;
use App\Provider\Form as FormProvider;
use App\Repository\Plugin\Filter\Parameter as FilterParameter;
use Application\Entity\Ingredient as IngredientEntity;
use Application\Repository\Ingredient as IngredientRepository;
use Application\Entity\DocumentIncomeIngredient as DocumentIncomeIngredientEntity;
use Application\Repository\DocumentIncomeIngredient as DocumentIncomeIngredientRepository;

class OrderController extends AbstractActionController
{
    /**
     * @var FilterForm
     */
    protected $filterForm;

    /**
     * @var EditForm
     */
    protected $editForm;

    /**
     * @var SorterForm
     */
    protected $sorterForm;

    /**
     * @var OrderRepository 
     */
    protected $orderRepository;

    /**
     * @var IngredientRepository
     */
    protected $ingredientRepository;

    /**
     * @var DocumentIncomeIngredientRepository
     */
    protected $documentIncomeIngredient;

    /**
     * @var PhpRenderer
     */
    protected $viewRenderer;

    /**
     * @param EntityManager $em
     * @param FormProvider  $formProvider
     * @param PhpRenderer   $viewRenderer
     */
    public function __construct(EntityManager $em, FormProvider $formProvider, PhpRenderer $viewRenderer)
    {
        $this->documentIncomeIngredient = $em->getRepository(DocumentIncomeIngredientEntity::class);
        $this->ingredientRepository = $em->getRepository(IngredientEntity::class);
        $this->orderRepository = $em->getRepository(OrderEntity::class);
        $this->sorterForm = $formProvider->provide(SorterForm::class);
        $this->filterForm = $formProvider->provide(FilterForm::class);
        $this->editForm = $formProvider->provide(EditForm::class);
        $this->viewRenderer = $viewRenderer;
    }

    public function indexAction()
    {
        $offset = (int) $this->getRequest()->getQuery('offset', 1);
        $limit  = (int) $this->getRequest()->getQuery('limit', RowsPerPage::LIMIT);

        $this->filterForm->setData($this->getRequest()->getQuery()->toArray())->isValid();
        $this->sorterForm->setData($this->getRequest()->getQuery()->toArray())->isValid();

        if (($status = $this->identity()->getOrderStatusForView())) {
            $fs = new FilterParameter('status', $status);
        } else {
            $fs = null;
        }

        if ($this->identity()->isDriver()) {
            $fd = new FilterParameter('driver', $this->identity()->getId());
        } else {
            $fd = null;
        }

        $parameters = array_merge([$fs, $fd], $this->filterForm->prepareAndGetData(), $this->sorterForm->prepareAndGetData());
        $paginator  = $this->orderRepository->paginatorFetchAll($limit, $offset, $parameters);

        return new ViewModel([
            'paginator' => $paginator,
            'filter' => $this->filterForm,
        ]);
    }

    public function approveAction()
    {
        $id = (int) $this->params('id');
        $order = $this->orderRepository->find($id);

        if (!$order) {
            return $this->getResponse()->setStatusCode(404);
        }

        $this->editForm->bind($order);
        if ($this->getRequest()->isPost()) {
            $this->editForm->setData($this->getRequest()->getPost());

            if ($this->editForm->isValid()) {
                $order->setStatus(OrderEntity::STATUS_APPROVED);
                $this->orderRepository->save($order);
                return $this->getResponse();
            }
        }

        $this->editForm->prepare();

        $view = new ViewModel(['form' => $this->editForm]);
        $view->setTerminal(true);
        return $view;
    }

    public function setStatusAction()
    {
        $id = (int) $this->params('id');
        $status = (int) $this->params('status');
        $order = $this->orderRepository->find($id);

        if (!$order) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Order was not found'));

            return $this->comeBack();
        }

        $order->setStatus($status);

        if ($status == OrderEntity::STATUS_COOKED) {
            $ingredients = $residues = [];
            foreach ($order->getProducts() as $product) {
                $receipt = $product->getReceiptParams();
                if (!empty($receipt['ingredient'])) {
                    foreach ($receipt['ingredient'] as $ingredient) {
                        if (!isset($ingredients[$ingredient['id']])) {
                            $ingredients[$ingredient['id']] = 0;
                        }
                        $ingredients[$ingredient['id']] += $ingredient['weight'];
                    }
                }
            }

            foreach ($ingredients as $id => $weight) {
                $ingredient = $this->ingredientRepository->find($id);
                if (!$ingredient) {
                    $this->flashMessenger()->addErrorMessage(
                        $this->translator()->translate('Ingredient with id="' . $id . '" not found')
                    );
                    return $this->comeBack();
                }

                if ($ingredient->getIngredientIncomes()->isEmpty()) {
                    $this->flashMessenger()->addErrorMessage(
                        $this->translator()->translate('For ingredient "' . $ingredient->getName()
                            . '" income not found')
                    );
                    return $this->comeBack();
                }

                foreach ($ingredient->getIngredientIncomes() as $ingredientIncome) {
                    if (!isset($residues[$ingredientIncome->getIngredient()->getId()])) {
                        $residues[$ingredientIncome->getIngredient()->getId()] = 0;
                    }
                    $residues[$ingredientIncome->getIngredient()->getId()] += $ingredientIncome->getResidue();
                }
            }

            foreach ($ingredients as $id => $weight) {
                $ingredient = $this->ingredientRepository->find($id);
                if ($residues[$id] < $weight) {
                    $this->flashMessenger()->addErrorMessage(
                        $this->translator()->translate('For ingredient "' . $ingredient->getName()
                            . '" residues less than necessary. Need ' . $weight . 'g.' . ' In store only ' . $residues[$id] . 'g')
                    );
                    return $this->comeBack();
                }
            }

            foreach ($ingredients as $id => $weight) {
                $ingredient = $this->ingredientRepository->find($id);
                $tmpWeight = $weight;
                foreach ($ingredient->getIngredientIncomes() as $ingredientIncome) {
                    if ($ingredientIncome->getResidue() >= $tmpWeight) {
                        $ingredientIncome->setResidue(
                            $ingredientIncome->getResidue() - $tmpWeight
                        );
                        $this->documentIncomeIngredient->save($ingredientIncome);
                        break;
                    } else {
                        $tmpWeight -= $ingredientIncome->getResidue();
                        $ingredientIncome->setResidue(0);
                        $this->documentIncomeIngredient->save($ingredientIncome);
                    }
                }
            }
        }

        $this->orderRepository->save($order);
        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Order status successfully change'));

        return $this->comeBack();
    }

    public function getReceiptAction()
    {
        $id = (int) $this->params('id');
        $order = $this->orderRepository->find($id);
        $productId = (int) $this->params('product_id');

        if (!$order) {
            return $this->getResponse()->setStatusCode(404);
        }

        foreach ($order->getProducts() as $product) {
            if ($product->getId() == $productId) {
                break; break;
            }
        }

        $view = new ViewModel(['product' => $product]);
        $view->setTerminal(true);
        return $view;
    }

    public function exportAction()
    {
        $orders = $this->orderRepository->findAll();
        $view = new ViewModel();
        $view->setTemplate('app-admin/order/export')
            ->setVariable('orders', $orders)
            ->setTerminal(true);

        $output = $this->viewRenderer->render($view);

        $response = $this->getResponse();

        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/csv')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="orders_' . date('Y-m-d_H:i:s') . '.csv"')
            ->addHeaderLine('Accept-Ranges', 'bytes')
            ->addHeaderLine('Content-Length', strlen($output));

        $response->setContent($output);

        return $response;
    }
}
