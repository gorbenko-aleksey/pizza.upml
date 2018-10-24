<?php

namespace AppAdmin\Controller\Listener;

use Zend\Mvc\MvcEvent;
use Zend\Console\Console;
use Zend\View\Model\JsonModel;
use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManagerInterface;
use Zend\Authentication\AuthenticationService;
use App\Controller\Listener\AbstractListener;
use Application\Service\WhiteIp;
use Zend\Session\SessionManager;

class Acl extends AbstractListener
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * Constructor
     *
     * @param ServiceManager $serviceManager
     * @param SessionManager $sessionManager
     */
    public function __construct(ServiceManager $serviceManager, SessionManager $sessionManager)
    {
        $this->serviceManager = $serviceManager;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Attach to an event manager
     *
     * @param  EventManagerInterface $events
     * @param  int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $namespace = $this->getNamespace();
        $sharedEvents = $events->getSharedManager();

        $this->listeners[] = $sharedEvents->attach($namespace, MvcEvent::EVENT_DISPATCH, [$this, 'acl'], 100);
    }

    /**
     * Acl
     *
     * @param MvcEvent $e
     *
     * @throws \Exception
     */
    public function acl(MvcEvent $e)
    {
        if (Console::isConsole()) {
            return;
        }

        $acl = $this->serviceManager->get('Application\Permissions\Acl\Acl');
        $auth = $this->serviceManager->get(AuthenticationService::class);
        $whiteIpService = $this->serviceManager->get(WhiteIp::class);
        $controller = $e->getRouteMatch()->getParam('controller');
        $action = $e->getRouteMatch()->getParam('action');

        if ($auth->hasIdentity()) {
            $sessionAuthTime = $this->sessionManager->getStorage()->authTime;
            /** @var \DateTime $passwordChangeTime */
            $passwordChangeTime = $auth->getIdentity()->getPasswordChangedAt();

            if ((!empty($passwordChangeTime) && $passwordChangeTime->getTimestamp() > $sessionAuthTime
                    && $this->sessionManager->getId() != $auth->getIdentity()->getPasswordChangeSessionId())
                || !$whiteIpService->isAllowedByIp($auth->getIdentity())
            ) {
                $auth->clearIdentity();
            }
        }

        if (!$acl->hasResource($resource = $controller . '::' . $action) && !$acl->hasResource($resource = $controller)) {
            throw new \Exception('Resource ' . $controller . '::' . $action . ' and ' . $controller . ' not found');
        }

        foreach ($auth->hasIdentity() ? $auth->getIdentity()->getRoleList() : ['guest'] as $role) {
            $isAllowed = $acl->isAllowed($role, $resource) ?: false;
        }

        if (!$isAllowed) {
            if (!$e->getRequest()->isXmlHttpRequest()) {
                $e->getResponse()->setStatusCode(301);
                $redirectUrl = $e->getRequest()->getRequestUri();
                $url = $e->getRouter()->assemble([], ['name' => 'admin/signin']);
                $e->getResponse()->getHeaders()->addHeaderLine('Location', $url . '?redirectUrl=' . $redirectUrl);
            } else {
                $e->setViewModel(new JsonModel(['success' => false]));
                $e->getResponse()->setStatusCode(401);
            }
            $e->getResponse()->send();
            $e->stopPropagation();
        }
    }
}
