<?php

namespace Application\Repository\Sorter;

use App\Repository\Plugin\Sorter\AbstractSorterQuery;
use Doctrine\ORM\QueryBuilder;

class Changer extends AbstractSorterQuery
{
    /**
     * @param QueryBuilder $qb
     * @param string $name
     * @param string $value
     */
    public function apply(QueryBuilder $qb, $name, $value)
    {
        $qb->leftJoin("{$this->entityName}.changer", 'user');
        $qb->orderBy('user.firstName', $value);
    }
}