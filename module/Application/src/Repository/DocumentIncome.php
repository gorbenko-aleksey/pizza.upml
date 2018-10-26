<?php

namespace Application\Repository;

use Zend\Stdlib\InitializableInterface;
use App\Repository\AbstractRepository;
use Application\Repository;
use App\Repository\Plugin;

class DocumentIncome extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register(
                'Sorter',
                new Plugin\Sorter\Sorter(
                    $this->_class, [],
                    ['default' => ['createdAt' => Plugin\Sorter\Sorter::DESC]]
                )
            );
    }
}
