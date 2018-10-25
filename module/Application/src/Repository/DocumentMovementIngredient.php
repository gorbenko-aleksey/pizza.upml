<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;

class DocumentMovementIngredient extends AbstractRepository
{
    /**
     * @param int       $ingredientId
     * @param int       $stageId
     * @param \DateTime $date
     *
     * @return null
     */
    public function findLastDocumentIdByIngredientIdStageIdDate($ingredientId, $stageId, \DateTime $date)
    {
        $query = $this->createQueryBuilder($this->getEntityName());
        $eb = $query->expr();
        $query->select($eb->max('movement_document.id'))
            ->leftJoin($this->getEntityName() . '.document', 'movement_document')
            ->where($eb->like('movement_document.createdAt', ':date'))
            ->andWhere($eb->eq($this->getEntityName() . '.ingredient', ':ingredient'))
            ->andWhere($eb->eq('movement_document.productionStage', ':productionStage'));

        $query->setParameters([
            'date' => $date->format('Y-m-d') . "%",
            'ingredient' => $ingredientId,
            'productionStage' => $stageId,
        ]);

        try {
            $result = $query->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
        } catch (NonUniqueResultException $e) {
            $result = null;
        }

        return $result;
    }
}
