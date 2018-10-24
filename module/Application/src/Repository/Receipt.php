<?php

namespace Application\Repository;

use Zend\Stdlib\InitializableInterface;
use App\Repository\AbstractRepository;
use App\Repository\Plugin;

class Receipt extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register(
                'Sorter',
                new Plugin\Sorter\Sorter(
                    $this->_class,
                    [Sorter\Standard::class => ['name', 'productWeight']],
                    ['default' => ['name' => Plugin\Sorter\Sorter::ASC]]
                )
            );
    }
}
