<?php

namespace Application;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module implements Feature\ConfigProviderInterface, Feature\BootstrapListenerInterface
{
    const VERSION = '3.0.3-dev';

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     *
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $sm = $e->getApplication()->getServiceManager();

        /* init listeners */
        $this->initListeners($sm);
    }

    /**
     * Init listeners
     *
     * @param ServiceLocatorInterface $sm
     */
    protected function initListeners(ServiceLocatorInterface $sm)
    {
        $em = $sm->get('doctrine.entitymanager.orm_default');

        // Init common listeners
        $loggerListener = $sm->get(Entity\Listener\Logger::class);
        $em->getEventManager()->addEventSubscriber($loggerListener);

        // Init personal listeners
        $userListener = $sm->get(Entity\Listener\User::class);
        $em->getConfiguration()->getEntityListenerResolver()->register($userListener);
    }
}
