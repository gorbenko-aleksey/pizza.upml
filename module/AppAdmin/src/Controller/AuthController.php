<?php

namespace AppAdmin\Controller;

use App\Provider\Form as FormProvider;
use AppAdmin\Form\Auth as AuthForm;
use Application\Entity;
use Application\Repository;
use Application\Service;
use Doctrine\ORM\EntityManager;
use Zend\Authentication;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\SessionManager;
use Zend\View;

/**
 * Class AuthController
 *
 * @package AppAdmin\Controller
 *
 * @method Entity\User identity
 * @method TranslatorInterface translator
 * @method Plugin\ComeBack comeBack
 */
class AuthController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SessionManager
     */
    private $sessionManager;

    /**
     * @var Authentication\AuthenticationService
     */
    private $authenticationService;

    /**
     * @var array
     */
    private $config;

    /**
     * @var FormProvider
     */
    protected $formProvider;

    /**
     * @var Service\EmailQueue
     */
    protected $emailQueueService;

    /**
     * @var View\Renderer\PhpRenderer
     */
    protected $viewRenderer;

    /**
     * AuthController constructor.
     *
     * @param EntityManager $em
     * @param SessionManager $sessionManager
     * @param Authentication\AuthenticationService $authenticationService
     * @param FormProvider $formProvider
     * @param Service\EmailQueue $emailQueueService
     * @param View\Renderer\PhpRenderer $viewRenderer
     * @param array $config
     */
    public function __construct(
        EntityManager $em,
        SessionManager $sessionManager,
        Authentication\AuthenticationService $authenticationService,
        FormProvider $formProvider,
        Service\EmailQueue $emailQueueService,
        View\Renderer\PhpRenderer $viewRenderer,
        array $config
    )
    {
        $this->em = $em;
        $this->sessionManager = $sessionManager;
        $this->authenticationService = $authenticationService;
        $this->formProvider = $formProvider;
        $this->emailQueueService = $emailQueueService;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    public function init()
    {
        $this->layout('app-admin/layout/layout-auth');
    }

    public function signinAction()
    {
        $success = true;
        /** @var AuthForm\Signin $form */
        $form = $this->formProvider->provide(AuthForm\Signin::class);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $this->authenticationService->getAdapter()->setCredential($data['password']);
                $this->authenticationService->getAdapter()->setIdentity($data['email']);
                $authenticationResult = $this->authenticationService->authenticate();

                if ($this->identity() && !$this->identity()->isCoworker()) {
                    $form->setMessages(['email' => [$this->translator()->translate('Invalid email')]]);
                    $success = false;
                }

                if ($success && !($success = ($authenticationResult->isValid()))) {
                    switch ($authenticationResult->getCode()) {
                        case Authentication\Result::FAILURE_IDENTITY_NOT_FOUND:
                            $message = $this->translator()->translate('Invalid email');
                            $form->setMessages(['email' => [$message]]);
                            break;
                        case Authentication\Result::FAILURE_CREDENTIAL_INVALID:
                            $message = $this->translator()->translate('Invalid password');
                            $form->setMessages(['password' => [$message]]);
                            break;
                        case Authentication\Result::FAILURE_UNCATEGORIZED:
                            $message = $this->translator()->translate('From your IP %s access denied');
                            $form->setMessages([
                                'email' => [
                                    sprintf($message, $this->getRequest()->getServer('REMOTE_ADDR'))
                                ]
                            ]);
                            break;
                        case Authentication\Result::FAILURE:
                            $message = $this->translator()->translate('User is not verified');
                            $form->setMessages(['email' => [$message]]);
                            break;
                        default:
                            $message = $this->translator()->translate('Invalid email');
                            $form->setMessages(['email' => [$message]]);
                    }
                }

                if ($success) {
                    $this->sessionManager->getStorage()->authTime = time();

                    if ($data['remember_me']) {
                        $this->sessionManager->rememberMe($this->config['session']['remember_me']);
                    }

                    return ($redirectUrl = $this->getRequest()->getQuery('redirectUrl'))
                        ? $this->redirect()->toUrl($redirectUrl)
                        : $this->redirect()->toRoute('admin');
                }
            }
        }

        return new View\Model\ViewModel(['form' => $form]);
    }

    public function signoutAction()
    {
        $this->authenticationService->clearIdentity();

        return $this->redirect()->toRoute('admin/signin');
    }

    public function forgotPasswordAction()
    {
        if ($this->identity()) {
            return $this->redirect()->toRoute('admin');
        }

        /** @var AuthForm\ForgotPassword $form */
        $form = $this->formProvider->provide(AuthForm\ForgotPassword::class);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                $user = $this->getUserRepository()->findOneByEmail(['email' => $form->get('email')->getValue()]);

                if ($user) {
                    $user->regenerateSecret();
                    $this->getUserRepository()->save($user);

                    $title = $this->viewRenderer->partial('app-admin/auth/mail/forgot-password-confirm/subject');
                    $body = $this->viewRenderer->partial('app-admin/auth/mail/forgot-password-confirm/body-html', [
                        'secret' => $user->getSecret(),
                    ]);

                    $this->emailQueueService->sendMail($body, $title, null, [$user->getEmail() => $user->getName()]);

                    return $this->redirect()->toRoute('admin/password-confirm-mail-sent');
                } else {
                    $form->setMessages(['email' => [$this->translator()->translate('User not found')]]);
                }
            }
        }

        return new View\Model\ViewModel([
            'form' => $form,
        ]);
    }

    public function forgotPasswordConfirmAction()
    {
        $secret = $this->params()->fromRoute('secret');
        /** @var AuthForm\ForgotPasswordConfirm $form */
        $form = $this->formProvider->provide(AuthForm\ForgotPasswordConfirm::class);

        $user = $this->getUserRepository()->findOneBySecret($secret);
        $viewModel = new View\Model\ViewModel();

        if (!$user) {
            $viewModel->setTemplate('app-admin/auth/forgot-password-user-not-found');
            return $viewModel;
        }

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $user->regenerateSecret()->setPassword($data['password']);
                $this->getUserRepository()->save($user);

                $subject = $this->viewRenderer->partial('app-admin/auth/mail/forgot-password-new/subject');
                $body = $this->viewRenderer->partial('app-admin/auth/mail/forgot-password-new/body-html', [
                    'email' => $user->getEmail(),
                    'password' => $data['password'],
                ]);

                $this->emailQueueService->sendMail($body, $subject, null, [$user->getEmail() => $user->getName()]);

                if ($this->identity()) {
                    $this->authenticationService->clearIdentity();
                }

                return $this->redirect()->toRoute('admin/password-changed-successfully');
            }
        }

        $viewModel->setVariables(['form' => $form]);

        return $viewModel;
    }

    public function passwordConfirmMailSentAction()
    {

    }

    public function passwordChangedSuccessfullyAction()
    {

    }

    /**
     * @return Repository\User
     */
    protected function getUserRepository()
    {
        return $this->em->getRepository(Entity\User::class);
    }
}
