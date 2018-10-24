<?php

namespace Application\Repository\Filter\User;

use App\Repository\Plugin\Filter\AbstractFilterQuery;
use Doctrine\ORM\QueryBuilder;

class Role extends AbstractFilterQuery
{
    /**
     * @param QueryBuilder $qb
     * @param string $name
     * @param string $value
     */
    public function apply(QueryBuilder $qb, $name, $value)
    {
        $qb->leftJoin("{$this->entityName}.{$name}", "r");
        $qb->andWhere("r.id IN (:$name)");
        $qb->setParameter($name, $value);
    }
}