<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Zend\Stdlib\InitializableInterface;
use App\Repository\Plugin;

class DocumentMovement extends AbstractRepository implements InitializableInterface
{
    public function init()
    {
        $this->pluginManager
            ->register('Filter', new Plugin\Filter\Filter($this->_class, [
                Filter\Equivalent::class => ['creator', 'operator', 'type', 'productionStage'],
                Filter\CreateOnOrAfter::class  => ['createdFrom'],
                Filter\CreateOnOrBefore::class => ['createdTo'],
                Filter\Like::class => ['description'],
            ]))
            ->register('Sorter', new Plugin\Sorter\Sorter($this->_class, [
                Sorter\Standard::class => ['createdAt', 'description'],
                Sorter\Creator::class => ['creator'],
                Sorter\Movement\Operator::class => ['operator'],
                Sorter\Movement\Type::class => ['type'],
                Sorter\Movement\ProductionStage::class => ['productionStage'],
            ], [
                'default' => ['createdAt' => Plugin\Sorter\Sorter::DESC],
            ]))
        ;
    }

    /**
     * @param $ingredientId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findCurrentDateWriteOffsByIngredientId($ingredientId)
    {
        $query = $this->createQueryBuilder($this->getEntityName());
        $query->leftJoin($this->getEntityName() . '.ingredients', 'write_off_ingredients')
            ->andWhere($query->expr()->like($this->getEntityName() . '.createdAt', ':date'))
            ->andWhere($query->expr()->eq('write_off_ingredients.ingredient', ':ingredientId'))
            ->setParameter('date', date('Y-m-d') . '%')
            ->setParameter('ingredientId', $ingredientId);

        return $this->createCollection($query->getQuery()->getResult());
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param array $stageIds
     *
     * @return array
     */
    public function findMovementDataForReport($startDate, $endDate, $stageIds)
    {
        $entityName = $this->getEntityName();

        $dqlWo = "SELECT sum(ingredients.weight) as weight, ingredient.id as ingredient_id, ingredient.name as ingredient_name, date(entity.createdAt) as created_at FROM $entityName entity " .
            "JOIN entity.ingredients ingredients " .
            "JOIN ingredients.ingredient ingredient " .
            "JOIN entity.productionStage productionStage " .
            "WHERE date(entity.createdAt) >= :startDate AND date(entity.createdAt) <= :endDate AND entity.type = " . \Application\Entity\DocumentMovementType::WRITE_OFF_ID .
            " AND productionStage.id IN(" . implode(', ', $stageIds) . ") " .
            "GROUP BY created_at, ingredients.ingredient";

        $queryWo = $this->_em->createQuery($dqlWo);
        $queryWo->setParameters([
            'startDate'  => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);

        $dqlUw = "SELECT sum(ingredients.weight) as weight, ingredient.id as ingredient_id, date(entity.createdAt) as created_at FROM $entityName entity " .
            "JOIN entity.ingredients ingredients " .
            "JOIN ingredients.ingredient ingredient " .
            "JOIN entity.productionStage productionStage " .
            "WHERE date(entity.createdAt) >= :startDate AND date(entity.createdAt) <= :endDate AND entity.type = " . \Application\Entity\DocumentMovementType::RETURN_ID .
            " AND productionStage.id IN(" . implode(', ', $stageIds) . ") " .
            "GROUP BY created_at, ingredients.ingredient";

        $queryUw = $this->_em->createQuery($dqlUw);
        $queryUw->setParameters([
            'startDate'  => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);

        return [ 'writeOff' => $queryWo->getResult(), 'return' => $queryUw->getResult()];
    }

    /**
     * @param int $ingredientId
     * @param int $stageId
     *
     * @return int|null
     */
    public function findWriteOffWeightByIngredientIdAndStageId($ingredientId, $stageId)
    {
        $entityName = $this->getEntityName();

        $dql = "SELECT SUM(write_offs.weight) AS weight FROM $entityName entity " .
            "JOIN entity.writeOffs write_offs " .
            "WHERE entity.productionStage = :stageId AND write_offs.ingredient = :ingredientId " .
            "GROUP BY write_offs.ingredient";

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'stageId'  => $stageId,
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
