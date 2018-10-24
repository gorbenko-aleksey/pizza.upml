<?php

namespace App\Repository\Plugin;

use App\Repository;
use Doctrine\ORM\Mapping;

abstract class AbstractPlugin implements Repository\Plugin\PluginInterface
{
    /**
     * @var QueryPluginInterface[]
     */
    protected $queryHandlers;

    /**
     * @var Mapping\ClassMetadata
     */
    protected $class;

    /**
     * FilterManager constructor.
     *
     * @param Mapping\ClassMetadata $class
     * @param array $handlers
     */
    public function __construct(Mapping\ClassMetadata $class, array $handlers)
    {
        $this->class = $class;
        $this->initialize($handlers);
    }

    /**
     * Init query handlers - creates classes instances
     *
     * @param array $handlers
     */
    protected function initialize(array $handlers)
    {
        $properties = [];

        foreach ($this->class->getReflectionProperties() as $property) {
            $properties[] = $property->name;
        }

        foreach ($handlers as $class => $names) {
            if (!class_exists($class)) {
                throw new Repository\Exception\RuntimeException(sprintf(
                    'Class with name "%s" was not found',
                    $class
                ));
            }

            if (!is_array($names)) {
                $names = [$names];
            }

            foreach ($names as $name) {
                if (!in_array($name, $properties)) {
                    throw new Repository\Exception\RuntimeException(sprintf(
                        'Entity "%s" has not property with name "%s"',
                        $this->class->name,
                        $name
                    ));
                }

                $this->queryHandlers[$name] = new $class($this->class->name);
            }
        }
    }
}