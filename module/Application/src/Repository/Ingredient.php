<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use Zend\Stdlib\InitializableInterface;
use App\Repository\Plugin;

class Ingredient extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register(
                'Sorter',
                new Plugin\Sorter\Sorter(
                    $this->_class,
                    [Sorter\Standard::class => ['name']],
                    ['default' => ['name' => Plugin\Sorter\Sorter::ASC]]
                )
            );
    }
}
