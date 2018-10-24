<?php

namespace App\Provider;

use Zend\ServiceManager\ServiceManager;

abstract class AbstractProvider
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * AbstractProvider constructor.
     *
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Provide object
     *
     * @param $className
     *
     * @return object
     */
    abstract public function provide($className);
}