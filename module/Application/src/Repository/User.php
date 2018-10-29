<?php

namespace Application\Repository;

use App\Repository\Plugin;
use App\Repository\AbstractRepository;
use Zend\Stdlib\InitializableInterface;

class User extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register(
                'Filter',
                new Plugin\Filter\Filter(
                    $this->_class,
                    [
                        Filter\User\Role::class => ['roles'],
                        Filter\Like::class      => ['email', 'firstName', 'lastName'],
                    ]
                )
            )
            ->register(
                'Sorter',
                new Plugin\Sorter\Sorter(
                    $this->_class,
                    [
                        Sorter\Standard::class => ['id', 'email', 'firstName', 'lastName'],
                    ],
                    [
                        'default' => ['id' => Plugin\Sorter\Sorter::DESC],
                    ]
                )
            );
    }

    /**
     * @param int $roleId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findByRoleId($roleId)
    {
        $query = $this->createQueryBuilder($this->getEntityName());

        $this->pluginManager->apply($query,  [new Plugin\Filter\Parameter('roles', $roleId)]);

        return $this->createCollection($query->getQuery()->getResult());
    }
}
