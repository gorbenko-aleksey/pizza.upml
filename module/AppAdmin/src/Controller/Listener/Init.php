<?php

namespace AppAdmin\Controller\Listener;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventManagerInterface;
use App\Controller\Listener\AbstractListener;

class Init extends AbstractListener
{
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

        $this->listeners[] = $sharedEvents->attach($namespace, MvcEvent::EVENT_DISPATCH, [$this, 'init'], 98);
    }

    /**
     * Init
     *
     * @param  MvcEvent $e
     */
    public function init(MvcEvent $e)
    {
        $controller = $e->getTarget();
        if (method_exists($controller, 'init')) {
            $controller->init();
        }
    }
}
