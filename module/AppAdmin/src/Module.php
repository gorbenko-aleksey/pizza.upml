<?php

namespace AppAdmin;

use Zend\ModuleManager\Feature;
use Zend\EventManager\EventInterface;
use Zend\Console\Adapter\AdapterInterface;

class Module implements
    Feature\ConfigProviderInterface,
    Feature\BootstrapListenerInterface,
    Feature\ConsoleUsageProviderInterface,
    Feature\ConsoleBannerProviderInterface
{
    const VERSION = '0.0.1dev';

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @inheritdoc
     */
    public function onBootstrap(EventInterface $e)
    {
        /* init controller listeners */
        $this->initControllerListeners($e);
    }

    /**
     * @inheritdoc
     */
    public function getConsoleBanner(AdapterInterface $console)
    {
        return "AppAdmin Module " . self::VERSION;
    }

    /**
     * @inheritdoc
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            'email-send-top' => 'Send emails from queue',
        ];
    }

    /**
     * Attach controller listeners
     *
     * @param EventInterface $e
     */
    public function initControllerListeners(EventInterface $e)
    {
        $sm = $e->getApplication()->getServiceManager();

        $controllerAclListener = $sm->get(Controller\Listener\Acl::class);
        $controllerAclListener->attach($e->getApplication()->getEventManager());

        $controllerInitListener = $sm->get(Controller\Listener\Init::class);
        $controllerInitListener->attach($e->getApplication()->getEventManager());

        $controllerLayoutListener = $sm->get(Controller\Listener\Layout::class);
        $controllerLayoutListener->attach($e->getApplication()->getEventManager());
    }
}
