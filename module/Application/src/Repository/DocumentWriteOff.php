<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use Zend\Stdlib\InitializableInterface;
use App\Repository\Plugin;

class DocumentWriteOff extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register(
                'Filter',
                new Plugin\Filter\Filter(
                    $this->_class,
                    [
                        Filter\Like::class             => ['createdAt'],
                        Filter\Equivalent::class       => ['creator'],
                        Filter\CreateOnOrAfter::class  => ['createdFrom'],
                        Filter\CreateOnOrBefore::class => ['createdTo'],
                    ]
                )
            )
            ->register(
                'Sorter',
                new Plugin\Sorter\Sorter(
                    $this->_class,
                    [
                        Sorter\Standard::class => ['createdAt'],
                        Sorter\Creator::class  => ['creator'],
                    ],
                    ['default' => ['createdAt' => Plugin\Sorter\Sorter::DESC]]
                )
            );
    }
}
