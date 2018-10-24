<?php

namespace AppAdmin\Controller;

use App\Provider\Form as FormProvider;
use AppAdmin\Form\Sorter as SorterForm;
use AppAdmin\Form\WhiteIp\Filter as FilterForm;
use AppAdmin\View\Helper\RowsPerPage;
use Application\Entity\WhiteIp as WhiteIpEntity;
use Application\Repository\WhiteIp as WhiteIpRepository;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AppAdmin\Form\WhiteIp\Edit as BaseEditForm;

/**
 * Class WhiteIpController
 *
 * @package AppAdmin\Controller
 *
 * @method UserEntity identity
 * @method TranslatorInterface translator
 * @method Plugin\ComeBack comeBack
 */
class WhiteIpController extends AbstractActionController
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
            'filter' => $filter,
            'paginator' => $paginator,
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params('id');

        if ($id) {
            $ip = $this->getRepository()->find($id);
        } else {
            $ip = new WhiteIpEntity();
        }

        if (!$ip) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Ip was not found.'));

            return $this->comeBack();
        }

        /** @var BaseEditForm $form */
        $form = $this->formProvider->provide(BaseEditForm::class);

        $form->bind($ip);

        if ($this->getRequest()->isPost()) {
            $values = $this->getRequest()->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $this->getRepository()->save($ip);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Ip has been saved.'));

                return $this->comeBack();
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function deleteAction()
    {
        $id = (int) $this->params('id');

        $ip = $this->getRepository()->find($id);

        if (!$ip) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Ip was not found.'));

            return $this->comeBack();
        }

        $this->getRepository()->remove($ip);

        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Ip has been deleted.'));

        return $this->comeBack();
    }

    /**
     * @return WhiteIpRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(WhiteIpEntity::class);
    }
}
