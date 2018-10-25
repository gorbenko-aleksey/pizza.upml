<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use App\Repository\Plugin;
use Zend\Stdlib\InitializableInterface;
use Application\Entity\Ingredient as IngredientEntity;

class DocumentIncomeIngredient extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register('Filter', new Plugin\Filter\Filter($this->_class, [
                Filter\Equivalent::class => ['ingredient'],
            ]))
            ->register('Sorter', new Plugin\Sorter\Sorter($this->_class, [
                Sorter\Standard::class => ['createdAt'],
            ], [
                'default' => ['createdAt' => Plugin\Sorter\Sorter::DESC],
            ]));
    }

    /**
     * @param IngredientEntity $ingredient
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findAvailableIncomesByIngredient(IngredientEntity $ingredient)
    {
        $query = $this->createQueryBuilder($this->getEntityName());
        $query->leftJoin($this->getEntityName() . '.document', 'income_document');
        $query->where($query->expr()->gt($this->getEntityName() . '.residue', 0));
        $query->andWhere($query->expr()->eq('income_document.status', \Application\Entity\DocumentIncome::STATUS_HELD));

        $this->pluginManager->apply($query,  [new Plugin\Filter\Parameter('ingredient', $ingredient->getId())]);
        $this->pluginManager->apply($query, [new Plugin\Sorter\Parameter('createdAt', Plugin\Sorter\Sorter::ASC)]);

        return $this->createCollection($query->getQuery()->getResult());
    }

    /**
     * @param IngredientEntity $ingredient
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findUsedIncomesByIngredient(IngredientEntity $ingredient)
    {
        $query = $this->createQueryBuilder($this->getEntityName());
        $query->where($query->expr()->neq($this->getEntityName() . '.residue', $this->getEntityName() . '.weight'));

        $this->pluginManager->apply($query,  [new Plugin\Filter\Parameter('ingredient', $ingredient->getId())]);

        return $this->createCollection($query->getQuery()->getResult());
    }

}
