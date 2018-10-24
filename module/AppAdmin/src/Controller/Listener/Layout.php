<?php

namespace AppAdmin\Controller\Listener;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventManagerInterface;
use App\Controller\Listener\AbstractListener;

class Layout extends AbstractListener
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

        $this->listeners[] = $sharedEvents->attach($namespace, MvcEvent::EVENT_DISPATCH, [$this, 'layout'], 99);
    }

    /**
     * Layout
     *
     * @param  MvcEvent $e
     */
    public function layout(MvcEvent $e)
    {
        $controller = $e->getTarget();
        $controller->layout('app-admin/layout/layout');
    }
}
