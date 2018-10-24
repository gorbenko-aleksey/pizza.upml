<?php

namespace Application\Repository\Filter;

use App\Repository\Plugin\Filter\AbstractFilterQuery;
use Doctrine\ORM\QueryBuilder;

class Like extends AbstractFilterQuery
{
    /**
     * @param QueryBuilder $qb
     * @param string $name
     * @param string $value
     */
    public function apply(QueryBuilder $qb, $name, $value)
    {
        foreach (['_', '%'] as $symbol) {
            $value = str_replace($symbol, "\\{$symbol}", $value);
        }

        $qb->andWhere("{$this->entityName}.{$name} LIKE :$name");
        $qb->setParameter($name, "%$value%");
    }
}