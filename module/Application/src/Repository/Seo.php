<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use Zend\Stdlib\InitializableInterface;
use App\Repository\Plugin;
use Doctrine\Common\Collections\Collection as CollectionInterface;

class Seo extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register('Filter', new Plugin\Filter\Filter($this->_class, [
                Filter\Like::class => ['title', 'description', 'keywords'],
                Filter\Equivalent::class => ['status'],
            ]))
            ->register('Sorter', new Plugin\Sorter\Sorter($this->_class, [
                Sorter\Standard::class => ['sort'],
            ], [
                'default' => ['sort' => Plugin\Sorter\Sorter::ASC],
            ]));
    }

    /**
     * Get all using plugins
     *
     * @param Plugin\ParameterInterface[] $parameters
     *
     * @return CollectionInterface
     */
    public function findAllWithParameters(array $parameters)
    {
        $qb = $this->createQueryBuilder($this->getEntityName());

        $this->pluginManager->apply($qb, $parameters);

        return $this->createCollection($qb->getQuery()->getResult());
    }
}
