<?php

namespace AppAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use App\Provider\Form as FormProvider;
use AppAdmin\View\Helper\RowsPerPage;
use Application\Entity\DocumentIncome as DocumentIncomeEntity;
use Application\Repository\DocumentIncome as DocumentIncomeRepository;
use AppAdmin\Form\Income\Edit as EditForm;

class IncomeController extends AbstractActionController
{
    /**
     * @var EditForm
     */
    protected $editForm;

    /**
     * @var DocumentIncomeRepository
     */
    protected $documentIncomeRepository;

    /**
     * @param EntityManager $em
     * @param FormProvider  $formProvider
     */
    public function __construct(EntityManager $em, FormProvider $formProvider)
    {
        $this->documentIncomeRepository = $em->getRepository(DocumentIncomeEntity::class);
        $this->editForm = $formProvider->provide(EditForm::class);
    }

    public function indexAction()
    {
        $offset = (int) $this->getRequest()->getQuery('offset', 1);
        $limit = (int) $this->getRequest()->getQuery('limit', RowsPerPage::LIMIT);

        $paginator = $this->documentIncomeRepository->paginatorFetchAll($limit, $offset, []);

        return new ViewModel([
            'paginator' => $paginator,
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params('id');

        if (($id = (int) $this->params('id'))) {
            $income = $this->documentIncomeRepository->find($id);
        } else {
            $income = new DocumentIncomeEntity();
        }

        if (!$income) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Income was not found'));

            return $this->comeBack();
        }

        $this->editForm->bind($income);
        if ($this->getRequest()->isPost()) {
            $this->editForm->setData($this->getRequest()->getPost());

            if ($this->editForm->isValid()) {
                foreach ($income->getIngredients() as $ingredient) {
                    $ingredient->setResidue($ingredient->getWeight());
                }
                $this->documentIncomeRepository->save($income);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Income has been saved'));

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
        $income = $this->documentIncomeRepository->find($id);

        if (!$income) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Income was not found'));

            return $this->comeBack();
        }

        $this->documentIncomeRepository->remove($income);
        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Income has been deleted'));

        return $this->comeBack();
    }
}
