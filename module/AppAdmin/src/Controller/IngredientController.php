<?php

namespace AppAdmin\Controller;

use App\Provider\Form as FormProvider;
use AppAdmin\Form\Ingredient\Edit as EditForm;
use AppAdmin\Form\Sorter as SorterForm;
use AppAdmin\View\Helper\RowsPerPage;
use Application\Entity\Ingredient as IngredientEntity;
use Application\Repository\Ingredient as IngredientRepository;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IngredientController extends AbstractActionController
{
    /**
     * @var EditForm
     */
    protected $editForm;

    /**
     * @var SorterForm
     */
    protected $sorterForm;

    /**
     * @var IngredientRepository
     */
    protected $ingredientRepository;

    /**
     * @param EntityManager $em
     * @param FormProvider $formProvider
     */
    public function __construct(EntityManager $em, FormProvider $formProvider)
    {
        $this->ingredientRepository = $em->getRepository(IngredientEntity::class);
        $this->sorterForm = $formProvider->provide(SorterForm::class);
        $this->editForm = $formProvider->provide(EditForm::class);
    }

    public function indexAction()
    {
        $offset = (int) $this->getRequest()->getQuery('offset', 1);
        $limit = (int) $this->getRequest()->getQuery('limit', RowsPerPage::LIMIT);
        $this->sorterForm->setData($this->getRequest()->getQuery()->toArray())->isValid();

        $parameters = $this->sorterForm->prepareAndGetData();
        $paginator = $this->ingredientRepository->paginatorFetchAll($limit, $offset, $parameters);

        return new ViewModel([
            'limit' => $limit,
            'paginator' => $paginator,
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params('id');

        if ($id) {
            $ingredient = $this->ingredientRepository->find($id);
        } else {
            $ingredient = new IngredientEntity();
        }

        if (!$ingredient) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Ingredient was not found'));

            return $this->comeBack();
        }

        $this->editForm->bind($ingredient);
        if ($this->getRequest()->isPost()) {
            $this->editForm->setData($this->getRequest()->getPost());

            if ($this->editForm->isValid()) {
                $this->ingredientRepository->save($ingredient);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Ingredient has been saved'));

                return $this->comeBack();
            }
        }

        $this->editForm->prepare();

        return new ViewModel([
            'form' => $this->editForm,
        ]);
    }

    public function deleteAction()
    {
        $id = (int) $this->params('id');
        $ingredient = $this->ingredientRepository->find($id);

        if (!$ingredient) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Ingredient was not found'));

            return $this->comeBack();
        }

        $this->ingredientRepository->remove($ingredient);
        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Ingredient has been deleted'));

        return $this->comeBack();
    }
}
