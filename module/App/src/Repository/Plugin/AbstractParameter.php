<?php

namespace App\Repository\Plugin;

abstract class AbstractParameter implements ParameterInterface
{
    /**
     * @var string
     */
    protected $property;

    /**
     * @var string
     */
    protected $value;

    /**
     * AbstractParameter constructor.
     *
     * @param string $property
     * @param string $value
     */
    public function __construct($property, $value)
    {
        $this->property = $property;
        $this->value = $value;
    }

    /**
     * Get property name
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}