<?php

namespace App\Controller\Listener;

use Zend\EventManager\AbstractListenerAggregate;

abstract class AbstractListener extends AbstractListenerAggregate
{
    /**
     * Namespace
     *
     * @var string
     */
    protected $namespace;

    /**
     * Set namespace
     *
     * @param $namespace
     *
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $names = explode('\\', $namespace);
        $this->namespace = array_shift($names);

        return $this;
    }

    /**
     * Get namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        if (is_null($this->namespace)) {
            $this->setNamespace(get_class($this));
        }

        return $this->namespace;
    }
}
