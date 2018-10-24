<?php

namespace Application\Repository\Filter;

use App\Repository\Plugin\Filter\AbstractFilterQuery;
use Doctrine\ORM\QueryBuilder;

class Equivalent extends AbstractFilterQuery
{
    /**
     * @param QueryBuilder $qb
     * @param string $name
     * @param string $value
     */
    public function apply(QueryBuilder $qb, $name, $value)
    {
        $qb->andWhere("{$this->entityName}.{$name} = :$name");
        $qb->setParameter($name, "$value");
    }
}