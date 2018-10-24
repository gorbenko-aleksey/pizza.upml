<?php

namespace Application\Repository\Sorter;

use App\Repository\Plugin\Sorter\AbstractSorterQuery;
use Doctrine\ORM\QueryBuilder;

class Standard extends AbstractSorterQuery
{
    /**
     * @param QueryBuilder $qb
     * @param string $name
     * @param string $type
     */
    public function apply(QueryBuilder $qb, $name, $type)
    {
        $qb->orderBy("{$this->entityName}.{$name}", $type);
    }
}