<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use Zend\Stdlib\InitializableInterface;
use App\Repository\Plugin;
use Application\Repository;

class Page extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register('Filter', new Plugin\Filter\Filter($this->_class, [
                Repository\Filter\Equivalent::class => ['status'],
                Repository\Filter\Like::class => ['name', 'code'],
            ]))
            ->register('Sorter', new Plugin\Sorter\Sorter($this->_class, [
                Repository\Sorter\Standard::class => ['id', 'status', 'name', 'code'],
            ], [
                'default' => ['id' => Plugin\Sorter\Sorter::ASC],
            ]));
    }
}
