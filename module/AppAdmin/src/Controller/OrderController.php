<?php

namespace AppAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use AppAdmin\View\Helper\RowsPerPage;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Order as OrderEntity;
use Application\Repository\Order as OrderRepository;
use AppAdmin\Form\Order\Filter as FilterForm;
use AppAdmin\Form\Sorter as SorterForm;
use App\Provider\Form as FormProvider;
use App\Repository\Plugin\Filter\Parameter as FilterParameter;

class OrderController extends AbstractActionController
{
    /**
     * @var FilterForm
     */
    protected $filterForm;

    /**
     * @var SorterForm
     */
    protected $sorterForm;

    /**
     * @var OrderRepository 
     */
    protected $orderRepository;

    /**
     * @param EntityManager $em
     * @param FormProvider $formProvider
     */
    public function __construct(EntityManager $em, FormProvider $formProvider)
    {
        $this->orderRepository = $em->getRepository(OrderEntity::class);
        $this->sorterForm = $formProvider->provide(SorterForm::class);
        $this->filterForm = $formProvider->provide(FilterForm::class);
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

        $parameters = array_merge([$fs], $this->filterForm->prepareAndGetData(), $this->sorterForm->prepareAndGetData());
        $paginator  = $this->orderRepository->paginatorFetchAll($limit, $offset, $parameters);

        return new ViewModel([
            'paginator' => $paginator,
            'filter' => $this->filterForm,
        ]);
    }

    public function deleteAction()
    {
        $id = (int) $this->params('id');
        $order = $this->orderRepository->find($id);

        if (!$order) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Order was not found'));

            return $this->comeBack();
        }

        $order->setStatus(OrderEntity::STATUS_DELETED);
        $this->orderRepository->save($order);
        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Order has been deleted'));

        return $this->comeBack();
    }
}
