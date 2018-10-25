<?php

namespace Application\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\EntityManager;
use Application\Entity\DocumentIncomeIngredient as IngredientIncomeEntity;
use Application\Entity\DocumentWriteOff as DocumentWriteOffEntity;

class IngredientIncome
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * IngredientIncome constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Event before persist document income ingredient
     *
     * @param IngredientIncomeEntity $ingredientIncome
     * @param LifecycleEventArgs $e
     */
    public function prePersist(IngredientIncomeEntity $ingredientIncome, LifecycleEventArgs $e)
    {
        $ingredientIncome->setResidue($ingredientIncome->getWeight());
    }

    /**
     * Event before update document income ingredient
     *
     * @param IngredientIncomeEntity $ingredientIncome
     * @param PreUpdateEventArgs     $e
     */
    public function preUpdate(IngredientIncomeEntity $ingredientIncome, PreUpdateEventArgs $e)
    {
        // if weight is changing
        if (array_key_exists('weight', $e->getEntityChangeSet())) {
            $ingredientIncome->setResidue($ingredientIncome->getWeight());
        }
    }

}
