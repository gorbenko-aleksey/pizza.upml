<?php

namespace AppAdmin\Controller;

use App\Provider\Form as FormProvider;
use AppAdmin\Form\Page\Edit as BaseEditForm;
use AppAdmin\Form\Page\Filter as FilterForm;
use AppAdmin\Form\Sorter as SorterForm;
use AppAdmin\View\Helper\RowsPerPage;
use Application\Entity\Page as PageEntity;
use Application\Repository\Page as PageRepository;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\View\Model\ViewModel;

/**
 * Class PageController
 *
 * @package AppAdmin\Controller
 *
 * @method UserEntity identity
 * @method TranslatorInterface translator
 * @method Plugin\ComeBack comeBack
 */
class PageController extends AbstractActionController
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
        $this->em = $em;
        $this->formProvider = $formProvider;
    }

    public function indexAction()
    {
        $offset = (int) $this->getRequest()->getQuery('offset', 1);
        $limit = (int) $this->getRequest()->getQuery('limit', RowsPerPage::LIMIT);

        /** @var FilterForm $filter */
        $filter = $this->formProvider->provide(FilterForm::class);
        $filter->setData($this->getRequest()->getQuery()->toArray())->isValid();

        /** @var SorterForm $filter */
        $sorter = $this->formProvider->provide(SorterForm::class);
        $sorter->setData($this->getRequest()->getQuery()->toArray())->isValid();

        $parameters = array_merge($filter->prepareAndGetData(), $sorter->prepareAndGetData());
        $paginator = $this->getRepository()->paginatorFetchAll($limit, $offset, $parameters);

        return new ViewModel([
            'limit' => $limit,
            'paginator' => $paginator,
            'filter' => $filter,
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params('id');

        if ($id) {
            $page = $this->getRepository()->find($id);
        } else {
            $page = new PageEntity();
        }

        if (!$page) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Page was not found.'));

            return $this->comeBack();
        }

        /** @var BaseEditForm $form */
        $form = $this->formProvider->provide(BaseEditForm::class);
        $form->bind($page);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                $this->getRepository()->save($page);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Page has been saved.'));

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
        $id = (int)$this->params('id');
        $page = $this->getRepository()->find($id);

        if (!$page) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Page was not found.'));

            return $this->comeBack();
        }

        $this->getRepository()->remove($page);

        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Page has been deleted.'));

        return $this->comeBack();
    }

    /**
     * @return PageRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(PageEntity::class);
    }
}
