<?php

namespace AppAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use App\Provider\Form as FormProvider;
use AppAdmin\Form\Receipt\Edit as EditForm;
use AppAdmin\Form\Sorter as SorterForm;
use AppAdmin\View\Helper\RowsPerPage;
use Application\Entity\Receipt as ReceiptEntity;
use Application\Repository\Receipt as ReceiptRepository;

class ReceiptController extends AbstractActionController
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
     * @var ReceiptRepository
     */
    protected $receiptRepository;

    public function __construct(EntityManager $em, FormProvider $formProvider)
    {
        $this->receiptRepository = $em->getRepository(ReceiptEntity::class);
        $this->sorterForm = $formProvider->provide(SorterForm::class);
        $this->editForm = $formProvider->provide(EditForm::class);
    }

    public function indexAction()
    {
        $offset = (int) $this->getRequest()->getQuery('offset', 1);
        $limit  = (int) $this->getRequest()->getQuery('limit', RowsPerPage::LIMIT);
        $this->sorterForm->setData($this->getRequest()->getQuery()->toArray())->isValid();

        $parameters = array_merge($this->sorterForm->prepareAndGetData());
        $paginator  = $this->receiptRepository->paginatorFetchAll($limit, $offset, $parameters);

        return new ViewModel([
            'paginator' => $paginator,
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params('id');

        if ($id) {
            $receipt = $this->receiptRepository->find($id);
        } else {
            $receipt = new ReceiptEntity();
        }

        if (!$receipt) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Receipt was not found'));

            return $this->comeBack();
        }

        $this->editForm->bind($receipt);
        if ($this->getRequest()->isPost()) {
            $this->editForm->setData($this->getRequest()->getPost());

            if ($this->editForm->isValid()) {
                $this->receiptRepository->save($receipt);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Receipt has been saved'));

                return $this->comeBack();
            }
        }

        return new ViewModel([
            'form' => $this->editForm,
        ]);
    }

    public function deleteAction()
    {
        $id = (int) $this->params('id');
        $receipt = $this->receiptRepository->find($id);

        if (!$receipt) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Receipt was not found'));

            return $this->comeBack();
        }

        $this->receiptRepository->remove($receipt);

        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Receipt has been deleted'));

        return $this->comeBack();
    }
}
