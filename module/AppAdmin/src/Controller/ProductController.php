<?php

namespace AppAdmin\Controller;

use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;
use App\Provider\Form as FormProvider;
use AppAdmin\View\Helper\RowsPerPage;
use Application\Entity\Product as ProductEntity;
use Application\Repository\Product as ProductRepository;
use AppAdmin\Form\Product\Filter as FilterForm;
use AppAdmin\Form\Sorter as SorterForm;
use Zend\Mvc\Controller\AbstractActionController;
use AppAdmin\Form\Product\Edit as BaseEditForm;

class ProductController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FormProvider
     */
    protected $formProvider;

    /**
     * UserController constructor.
     *
     * @param EntityManager $em
     * @param FormProvider $formProvider
     */
    public function __construct(EntityManager $em, FormProvider $formProvider)
    {
        $this->em           = $em;
        $this->formProvider = $formProvider;
    }

    public function indexAction()
    {
        $offset = (int) $this->getRequest()->getQuery('offset', 1);
        $limit  = (int) $this->getRequest()->getQuery('limit', RowsPerPage::LIMIT);

        /** @var FilterForm $filter */
        $filter = $this->formProvider->provide(FilterForm::class);
        $filter->setData($this->getRequest()->getQuery()->toArray())->isValid();

        /** @var SorterForm $filter */
        $sorter = $this->formProvider->provide(SorterForm::class);
        $sorter->setData($this->getRequest()->getQuery()->toArray())->isValid();

        $parameters = array_merge($filter->prepareAndGetData(), $sorter->prepareAndGetData());
        $paginator  = $this->getRepository()->paginatorFetchAll($limit, $offset, $parameters);

        return new ViewModel([
            'limit'     => $limit,
            'filter'    => $filter,
            'paginator' => $paginator,
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params('id');

        if ($id) {
            $product = $this->getRepository()->find($id);
        } else {
            $product = new ProductEntity();
        }

        if (!$product) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Product was not found.'));

            return $this->comeBack();
        }

        /** @var BaseEditForm $form */
        $form = $this->formProvider->provide(BaseEditForm::class);
        $form->bind($product);

        if ($this->getRequest()->isPost()) {
            $form->setData(array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            ));

            if ($form->isValid()) {
                $form->prepareFiles();
                $this->getRepository()->save($product);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Product has been saved.'));

                return $this->comeBack();
            }
        }

        $form->prepare();

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function deleteAction()
    {
        $id  = (int) $this->params('id');
        $product = $this->getRepository()->find($id);

        if (!$product) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Product was not found.'));

            return $this->comeBack();
        }

        $this->getRepository()->remove($product);

        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Product has been deleted.'));

        return $this->comeBack();
    }

    /**
     * @return ProductRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(ProductEntity::class);
    }
}
