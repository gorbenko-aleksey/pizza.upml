<?php

namespace Application\SimpleObject;

class SeoData
{
    /**
     * @var string
     */
    private $parentString = '';

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var string
     */
    private $name;

    /**
     * SeoData constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addPlaceholder($name, $value)
    {
        if (($name != 'parent') && ($name != 'empty')) {
            $this->params[$name] = $value;
        }
    }

    /**
     * @param string $pattern
     */
    public function applyPattern($pattern)
    {
        $names = $values = [];

        foreach ($this->params as $k => $v) {
            $names[] = '{' . $k . '}';
            $values[] = $v;
        }

        $patt = array_merge(['{parent}', '{empty}'], $names);
        $data = array_merge([$this->parentString, ''], $values);

        $this->parentString = str_replace($patt, $data, $pattern);
    }

    /**
     * @param bool $hidePlaceholders
     *
     * @return string
     */
    public function getResult($hidePlaceholders = true)
    {
        return $hidePlaceholders ? preg_replace('/{[^}]*}/', '', $this->parentString) : $this->parentString;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
