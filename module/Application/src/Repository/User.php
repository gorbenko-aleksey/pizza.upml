<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use App\Repository\Plugin;
use Zend\Stdlib\InitializableInterface;

class User extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register('Filter', new Plugin\Filter\Filter($this->_class, [
                Filter\Like::class => ['email', 'firstName', 'lastName'],
                Filter\User\Role::class => ['roles'],
            ]))
            ->register('Sorter', new Plugin\Sorter\Sorter($this->_class, [
                Sorter\Standard::class => ['id', 'email', 'firstName', 'lastName'],
            ], [
                'default' => ['id' => Plugin\Sorter\Sorter::DESC],
            ]));
    }
}
