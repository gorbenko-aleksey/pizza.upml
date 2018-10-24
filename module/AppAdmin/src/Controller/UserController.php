<?php

namespace AppAdmin\Controller;

use App\Provider\Form as FormProvider;
use AppAdmin\Form\User as UserForm;
use AppAdmin\Form;
use AppAdmin\View\Helper\RowsPerPage;
use Application\Entity;
use Application\Repository;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\View\Model\ViewModel;

/**
 * Class UserController
 *
 * @package AppAdmin\Controller
 *
 * @method Entity\User identity
 * @method TranslatorInterface translator
 * @method Plugin\ComeBack comeBack
 */
class UserController extends AbstractActionController
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

        /** @var UserForm\Filter $filter */
        $filter = $this->formProvider->provide(UserForm\Filter::class);
        $filter->setData($this->getRequest()->getQuery()->toArray())->isValid();

        /** @var Form\Sorter $sorter */
        $sorter = $this->formProvider->provide(Form\Sorter::class);
        $sorter->setData($this->getRequest()->getQuery()->toArray())->isValid();

        $parameters = array_merge($filter->prepareAndGetData(), $sorter->prepareAndGetData());
        $paginator = $this->getRepository()->paginatorFetchAll($limit, $offset, $parameters);

        return new ViewModel([
            'paginator' => $paginator,
            'filter' => $filter,
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params('id');

        if ($id) {
            $user = $this->getRepository()->find($id);
        } else {
            $user = new Entity\User();
        }

        if (!$user) {
            $this->notExistEntityHandler();
            return $this->comeBack();
        }

        /** @var UserForm\Edit $form */
        $form = $this->formProvider->provide(UserForm\Edit::class);

        $form->bind($user);

        if ($this->getRequest()->isPost()) {
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $form->setData($data);

            if ($form->isValid()) {
                $this->getRepository()->save($user);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('User has been saved'));

                return $this->comeBack();
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editPasswordAction()
    {
        $id = (int) $this->params('id');

        /** @var UserForm\EditPassword $form */
        $form = $this->formProvider->provide(UserForm\EditPassword::class);

        $user = $this->getRepository()->find($id);

        if (!$user) {
            $this->notExistEntityHandler();
            return $this->redirect()->toRoute('admin/user');
        }

        $form->setData($this->getRequest()->getPost());

        if ($this->getRequest()->isPost()) {
            if ($form->isValid()) {
                $password = $form->getData()['password'];
                $user->setPassword($password);

                $this->em->persist($user);
                $this->em->flush();

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('User password has been changed'));

                return $this->comeBack();
            }
        }

        $form->clearFields();

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function deleteAction()
    {
        $id = (int)$this->params('id');
        $user = $this->getRepository()->find($id);

        if (!$user) {
            $this->notExistEntityHandler();
            return $this->comeBack();
        }

        if ($user->getId() === $this->identity()->getId()) {
            $this->flashMessenger()->addWarningMessage($this->translator()->translate('You can not delete your profile'));
            return $this->comeBack();
        }

        $this->getRepository()->remove($user);

        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('User has been deleted'));

        return $this->comeBack();
    }

    /**
     * If user was not found
     */
    protected function notExistEntityHandler()
    {
        $this->flashMessenger()->addErrorMessage($this->translator()->translate('User was not found'));
    }

    /**
     * @return Repository\User
     */
    protected function getRepository()
    {
        return $this->em->getRepository(Entity\User::class);
    }
}
