<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use Zend\Stdlib\InitializableInterface;
use App\Repository\Plugin;

class WhiteIp extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register('Filter', new Plugin\Filter\Filter($this->_class, [
                Filter\Like::class => ['ip', 'comment'],
            ]))
            ->register('Sorter', new Plugin\Sorter\Sorter($this->_class, [
                Sorter\Standard::class => ['id', 'ip', 'comment', 'createdAt', 'changedAt'],
                Sorter\Creator::class => 'creator',
                Sorter\Changer::class => 'changer',
            ], [
                'default' => ['id' => Plugin\Sorter\Sorter::DESC],
            ]));
    }
}
