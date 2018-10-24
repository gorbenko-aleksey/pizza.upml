<?php

namespace AppAdmin\Controller;

use Zend\View\Model\ViewModel;
use App\Provider\Form as FormProvider;
use AppAdmin\View\Helper\RowsPerPage;
use Application\Entity\Category as CategoryEntity;
use Application\Repository\Category as CategoryRepository;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use AppAdmin\Form\Category\Edit as BaseEditForm;
use AppAdmin\Form\Category\Filter as FilterForm;
use AppAdmin\Form\Sorter as SorterForm;

class CategoryController extends AbstractActionController
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
        $parent = (int) $this->getRequest()->getQuery('parent', CategoryEntity::ROOT_ID);

        /** @var FilterForm $filter */
        $filter = $this->formProvider->provide(FilterForm::class);
        $filter->setData($this->getRequest()->getQuery()->toArray())->isValid();

        /** @var SorterForm $filter */
        $sorter = $this->formProvider->provide(SorterForm::class);
        $sorter->setData($this->getRequest()->getQuery()->toArray())->isValid();

        $list       = $this->getRepository()->getList();
        $parameters = array_merge(
            $filter->prepareAndGetData(), $sorter->prepareAndGetData(),
            [new \App\Repository\Plugin\Filter\Parameter('parent', $parent)]
        );
        $paginator  = $this->getRepository()->paginatorFetchAll($limit, $offset, $parameters);

        return new ViewModel([
            'limit'     => $limit,
            'paginator' => $paginator,
            'filter'    => $filter,
            'list'      => $list,
            'parent'    => $parent,
        ]);
    }

    public function editAction()
    {
        $parent  = (int) $this->getRequest()->getQuery('parent', CategoryEntity::ROOT_ID);
        $current = (int) $this->params('current');

        if ($current) {
            $category = $this->getRepository()->find($current);
        } else {
            $category = new CategoryEntity();
            $category->setParent(
                $this
                    ->getRepository()
                    ->find($parent)
            );
        }

        if (!$category) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Category was not found.'));

            return $this->comeBack();
        }

        /** @var BaseEditForm $form */
        $form = $this->formProvider->provide(BaseEditForm::class);
        $form->bind($category);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                $this->getRepository()->save($category);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Category has been saved.'));

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
        $current  = (int) $this->params('current');
        $category = $this->getRepository()->find($current);

        if (!$category) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Category was not found.'));

            return $this->comeBack();
        }

        $this->getRepository()->remove($category);

        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Category has been deleted.'));

        return $this->comeBack();
    }

    /**
     * @return CategoryRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(CategoryEntity::class);
    }
}
