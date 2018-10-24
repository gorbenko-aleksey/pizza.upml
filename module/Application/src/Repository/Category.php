<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use App\Repository\Plugin;
use Zend\Stdlib\InitializableInterface;
use Application\Repository;

class Category extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register('Filter', new Plugin\Filter\Filter($this->_class, [
                Repository\Filter\Equivalent::class => ['status', 'parent'],
                Repository\Filter\Like::class       => ['name', 'code'],
            ]))
            ->register('Sorter', new Plugin\Sorter\Sorter(
                $this->_class, 
                [
                    Repository\Sorter\Standard::class => ['id', 'status', 'name', 'code'],
                ],
                [
                    'default' => ['id' => Plugin\Sorter\Sorter::ASC],
                ]
            ));
    }

    /**
     * Get list of categories
     * 
     * @param bool $withTabulation
     * 
     * @return array
     */
    public function getList($withTabulation = true)
    {
        $categories = $this->findBy(['parent' => null]);

        $this->buildList($categories);

        $list = $this->list;
        $this->list = [];

        return $list;
    }

    /**
     * Build list of categories
     *
     * @param $categories
     * @param int $level
     * @param bool $withTabulation
     *
     * @param string $tabulator
     */
    protected function buildList($categories, int $level = 0, bool $withTabulation = true, string $tabulator = '-')
    {
        foreach ($categories as $category){
            $prefix = $withTabulation ? str_repeat($tabulator, $category->getLevel()) : '';

            $this->list[$category->getId()] = $prefix . $category->getName();

            $this->buildList($category->getChildren(), $level, $withTabulation);
        }
    }
}
