<?php

namespace App\Repository\Plugin\Filter;

use App\Repository\Plugin\QueryPluginInterface;

abstract class AbstractFilterQuery implements QueryPluginInterface
{
    /**
     * @var string
     */
    protected $entityName;

    /**
     * AbstractFilterQuery constructor.
     *
     * @param string $entityName
     */
    public function __construct($entityName)
    {
        $this->entityName = $entityName;
    }
}