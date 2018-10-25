<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Zend\Stdlib\InitializableInterface;
use App\Repository\Plugin;

class DocumentIncome extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register('Filter', new Plugin\Filter\Filter($this->_class, [
                Filter\Like::class => ['createdAt'],
                Filter\Equivalent::class => ['creator', 'status'],
                Filter\CreateOnOrAfter::class  => ['createdFrom'],
                Filter\CreateOnOrBefore::class => ['createdTo'],
            ]))
            ->register('Sorter', new Plugin\Sorter\Sorter($this->_class, [
                Sorter\Standard::class => ['createdAt', 'status', 'description'],
                Sorter\Creator::class => 'creator',
            ], [
                'default' => ['createdAt' => Plugin\Sorter\Sorter::DESC],
            ]))
        ;
    }

    /**
     * @param int $ingredientId
     *
     * @return int|null
     */
    public function findResidueWeightByIngredientId($ingredientId)
    {
        $entityName = $this->getEntityName();

        $dql = "SELECT SUM(ingredients.residue) AS weight FROM $entityName entity " .
            "JOIN entity.ingredients ingredients " .
            "WHERE ingredients.ingredient = :ingredientId " .
            "GROUP BY ingredients.ingredient";

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'ingredientId' => $ingredientId,
        ]);

        try {
            $result = $query->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
        } catch (NonUniqueResultException $e) {
            $result = null;
        }

        return $result;
    }

}
