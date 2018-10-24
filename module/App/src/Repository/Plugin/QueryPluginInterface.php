<?php

namespace App\Repository\Plugin;

use Doctrine\ORM\QueryBuilder;

interface QueryPluginInterface
{
    /**
     * @param QueryBuilder $qb
     * @param string $name
     * @param string $value
     */
    public function apply(QueryBuilder $qb, $name, $value);
}