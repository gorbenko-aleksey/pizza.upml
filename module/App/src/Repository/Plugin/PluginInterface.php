<?php

namespace App\Repository\Plugin;

use Doctrine\ORM\QueryBuilder;

interface PluginInterface
{
    /**
     * Apply query handlers
     *
     * @param QueryBuilder $qb
     * @param array $parameters
     */
    public function apply(QueryBuilder $qb, array $parameters);
}