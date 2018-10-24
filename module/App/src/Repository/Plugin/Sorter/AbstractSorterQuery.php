<?php

namespace App\Repository\Plugin\Sorter;

use Doctrine\ORM\QueryBuilder;
use App\Repository\Plugin\QueryPluginInterface;

abstract class AbstractSorterQuery implements QueryPluginInterface
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