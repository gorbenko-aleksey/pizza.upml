<?php

namespace App\Repository\Plugin\Sorter;

use Doctrine\ORM\Mapping;
use Doctrine\ORM\QueryBuilder;
use App\Repository;

class Sorter extends Repository\Plugin\AbstractPlugin
{
    /**
     * @var string
     */
    const ASC  = 'ASC';

    /**
     * @var string
     */
    const DESC = 'DESC';

    /**
     * @var Repository\Plugin\Sorter\Parameter|null
     */
    protected $default;

    /**
     * FilterManager constructor.
     *
     * @param Mapping\ClassMetadata $class
     * @param QueryPluginInterface[] $sorters
     * @param array $options
     */
    public function __construct(Mapping\ClassMetadata $class, array $sorters, array $options = [])
    {
        parent::__construct($class, $sorters);

        $this->setOptions($options);
    }

    /**
     * @param QueryBuilder $qb
     * @param array $parameters
     *
     * @return $this
     */
    public function apply(QueryBuilder $qb, array $parameters)
    {
        $parameters = $this->filterParameters($parameters);

        if (empty($parameters) && $this->default) {
            $parameters[] = $this->default;
        }

        foreach ($parameters as $parameter) {
            $property = $parameter->getProperty();
            $type = $parameter->getValue();

            if(!isset($this->queryHandlers[$property])){
                continue;
            }

            $this->queryHandlers[$property]->apply($qb, $property, $type);
        }

        return $this;
    }

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
            return $parameter instanceof Repository\Plugin\Sorter\Parameter;
        });
    }

    /**
     * Prepare options
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        if(!empty($options['default'])){
            $key = array_keys($options['default'])[0];
            $type = $options['default'][$key];

            if (!in_array(strtoupper($type), [self::ASC, self::DESC])) {
                throw new Repository\Exception\RuntimeException(sprintf(
                    "Unknown sort type '%s'",
                    $type
                ));
            }

            $this->default = new Repository\Plugin\Sorter\Parameter($key, $type);
        }
    }
}