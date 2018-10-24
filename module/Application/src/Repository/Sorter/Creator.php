<?php

namespace Application\Repository\Sorter;

use App\Repository\Plugin\Sorter\AbstractSorterQuery;
use Doctrine\ORM\QueryBuilder;

class Creator extends AbstractSorterQuery
{
    /**
     * @param QueryBuilder $qb
     * @param string $name
     * @param string $value
     */
    public function apply(QueryBuilder $qb, $name, $value)
    {
        $qb->leftJoin("{$this->entityName}.creator", 'user');
        $qb->orderBy('user.firstName', $value);
    }
}