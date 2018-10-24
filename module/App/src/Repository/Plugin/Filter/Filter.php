<?php

namespace App\Repository\Plugin\Filter;

use Doctrine\ORM\QueryBuilder;
use App\Repository;

class Filter extends Repository\Plugin\AbstractPlugin
{
    /**
     * Get parameters for current plugin
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function filterParameters(array $parameters)
    {
        return array_filter($parameters, function($parameter){
            return $parameter instanceof Repository\Plugin\Filter\Parameter;
        });
    }

    /**
     * Modify query
     *
     * @param QueryBuilder $qb
     * @param array $parameters
     */
    public function apply(QueryBuilder $qb, array $parameters)
    {
        $parameters = $this->filterParameters($parameters);

        foreach ($parameters as $parameter) {
            $property = $parameter->getProperty();
            $value = $parameter->getValue();

            if(empty($this->queryHandlers[$property]) || $value === ''){
                continue;
            }

            $this->queryHandlers[$property]->apply($qb, $property, $value);
        }
    }
}