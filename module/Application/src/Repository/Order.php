<?php

namespace Application\Repository;

use Zend\Stdlib\InitializableInterface;
use App\Repository\AbstractRepository;
use Application\Repository;
use App\Repository\Plugin;

class Order extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register(
                'Filter',
                new Plugin\Filter\Filter(
                    $this->_class,
                    [
                        Repository\Filter\Equivalent::class => ['status'],
                    ]
                )
            )
            ->register(
                'Sorter',
                new Plugin\Sorter\Sorter(
                    $this->_class,
                    [
                        Sorter\Standard::class => [
                            'id', 'status', 'createdAt', 'changedAt',
                        ],
                    ],
                    ['default' => ['createdAt' => Plugin\Sorter\Sorter::DESC]]
                )
            );
    }
}
