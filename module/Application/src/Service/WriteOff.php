<?php

namespace Application\Service;

use Application\Entity\DocumentMovement as DocumentMovementEntity;
use Application\Entity\DocumentMovementType;
use Application\Entity\DocumentWriteOff as DocumentWriteOffEntity;
use Application\Entity\DocumentMovementIngredient as DocumentMovementIngredientEntity;
use Application\Entity\DocumentIncomeIngredient as DocumentIncomeIngredientEntity;
use Application\Repository\DocumentIncomeIngredient as DocumentIncomeIngredientRepository;
use Application\Repository\DocumentWriteOff as DocumentWriteOffRepository;
use Application\Repository\DocumentMovementIngredient as DocumentMovementIngredientRepository;
use Application\Service\Exception\WriteOffLogicalException;
use Doctrine\ORM\EntityManager;
use Zend\I18n\Translator\TranslatorInterface;

class WriteOff
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Movement constructor.
     *
     * @param EntityManager       $em
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManager $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @param DocumentMovementEntity $movement
     *
     * @throws WriteOffLogicalException
     */
    public function commit(DocumentMovementEntity $movement)
    {
        $movementIngredients = $movement->getIngredients();

        if ($movementIngredients->isEmpty()) {
            return;
        }

        /** @var DocumentMovementIngredientEntity $movementIngredient */
        foreach ($movementIngredients as $movementIngredient) {
            if ($movement->getType()->getId() === DocumentMovementType::WRITE_OFF_ID) {
                $this->writeOff($movementIngredient);
            } else {
                $this->unWriteOff($movementIngredient);
            }
        }
    }

    /**
     * @param DocumentMovementEntity $movement
     *
     * @throws WriteOffLogicalException
     */
    public function rollback(DocumentMovementEntity $movement)
    {
        $movementIngredients = $movement->getIngredients();

        if ($movementIngredients->isEmpty()) {
            return;
        }

        /** @var DocumentMovementIngredientEntity $movementIngredient */
        foreach ($movementIngredients as $movementIngredient) {
            $this->unWriteOffByWriteOffs($movementIngredient);
        }
    }

    /**
     * Process write off from oldest supply for ingredient.
     *
     * @param DocumentMovementIngredientEntity $movementIngredient
     *
     * @throws WriteOffLogicalException
     */
    private function writeOff(DocumentMovementIngredientEntity $movementIngredient)
    {
        $ingredient = $movementIngredient->getIngredient();
        $weightToProcess = $movementIngredient->getWeight();
        $availableIncomes = $this->getDocumentIncomeIngredientRepository()
            ->findAvailableIncomesByIngredient($ingredient);

        if ($availableIncomes->isEmpty()) {
            throw new WriteOffLogicalException($this->translator->translate('no incomes found for ingredient') . ' '
                . $ingredient->getName());
        }

        /** @var DocumentIncomeIngredientEntity $currentIncome */
        $currentIncome = $availableIncomes->first();

        while ($weightToProcess > 0) {
            if (!$currentIncome instanceof DocumentIncomeIngredientEntity) {
                throw new WriteOffLogicalException($this->translator->translate('not enough incomes found for ingredient')
                    . ' ' . $ingredient->getName());
            }

            $residue = $currentIncome->getResidue();

            if ($residue >= $weightToProcess) {
                $residue -= $weightToProcess;
                $writtenOff = $weightToProcess;
                $weightToProcess = 0;
            } else {
                $weightToProcess -= $residue;
                $writtenOff = $residue;
                $residue = 0;
            }

            $currentIncome->setResidue($residue);
            $this->getDocumentIncomeIngredientRepository()->save($currentIncome);

            $documentWriteOff = new DocumentWriteOffEntity();
            $documentWriteOff
                ->setIngredient($ingredient)
                ->setWeight($writtenOff)
                ->setMovement($movementIngredient->getDocument());
            $this->getDocumentWriteOffRepository()->save($documentWriteOff);

            $currentIncome = $availableIncomes->next();
        }
    }

    /**
     * Process unwriteoff from youngest supply for ingredient.
     *
     * @param DocumentMovementIngredientEntity $movementIngredient
     *
     * @throws WriteOffLogicalException
     */
    private function unWriteOff(DocumentMovementIngredientEntity $movementIngredient)
    {
        $ingredient = $movementIngredient->getIngredient();
        $weightToProcess = $movementIngredient->getWeight();
        $availableIncomes = $this->getDocumentIncomeIngredientRepository()->findUsedIncomesByIngredient($ingredient);

        if ($availableIncomes->isEmpty()) {
            throw new WriteOffLogicalException($this->translator->translate('no residues found'));
        }

        /** @var DocumentIncomeIngredientEntity $currentIncome */
        $currentIncome = $availableIncomes->first();

        while ($weightToProcess > 0) {
            if (!$currentIncome instanceof DocumentIncomeIngredientEntity) {
                throw new WriteOffLogicalException($this->translator->translate('not enough residues found for ingredient')
                    . ' ' . $ingredient->getName());
            }

            $currentIncomeResidue = $currentIncome->getResidue();
            $incomeUsedWeight = $currentIncome->getWeight() - $currentIncomeResidue;

            if ($incomeUsedWeight >= $weightToProcess) {
                $refund = $weightToProcess;
                $weightToProcess = 0;
            } else {
                $weightToProcess -= $incomeUsedWeight;
                $refund = $incomeUsedWeight;
            }

            $currentIncome->setResidue($currentIncomeResidue + $refund);
            $this->getDocumentIncomeIngredientRepository()->save($currentIncome);

            $documentWriteOff = new DocumentWriteOffEntity();
            $documentWriteOff
                ->setIngredient($ingredient)
                ->setWeight(-$refund)
                ->setMovement($movementIngredient->getDocument());
            $this->getDocumentWriteOffRepository()->save($documentWriteOff);

            $currentIncome = $availableIncomes->next();
        }
    }

    /**
     * Remove all write offs and restore ingredient residue
     *
     * @param DocumentMovementIngredientEntity $movementIngredient
     *
     * @throws WriteOffLogicalException
     */
    private function unWriteOffByWriteOffs(DocumentMovementIngredientEntity $movementIngredient)
    {
        $weightToProcess = 0;
        $document = $movementIngredient->getDocument();
        $writeOffs = $document->getWriteOffs();
        $ingredient = $movementIngredient->getIngredient();

        $lastMovementId = $this->getDocumentMovementIngredientRepository()
            ->findLastDocumentIdByIngredientIdStageIdDate($movementIngredient->getIngredient()->getId(),
                $document->getProductionStage()->getId(), $document->getCreatedAt());

        if ($lastMovementId === null || $lastMovementId > $document->getId()) {
            throw new WriteOffLogicalException(
                $this->translator->translate('not last movement for ingredient') . ' ' . $ingredient->getName() . ' ' .
                $this->translator->translate('on stage') . ' ' . $document->getProductionStage()->getName()
            );
        }

        /** @var DocumentWriteOffEntity $writeOff */
        foreach ($writeOffs as $writeOff) {
            if ($writeOff->getIngredient()->getId() === $ingredient->getId()) {
                $weightToProcess += $writeOff->getWeight();
                $this->getDocumentWriteOffRepository()->remove($writeOff);
            }
        }

        // if current movement is write of to production then we must increase residues
        if ($movementIngredient->getDocument()->getType()->getId() === DocumentMovementType::WRITE_OFF_ID) {
            $availableIncomes = $this->getDocumentIncomeIngredientRepository()
                ->findUsedIncomesByIngredient($ingredient);

            /** @var DocumentIncomeIngredientEntity $currentIncome */
            $currentIncome = $availableIncomes->first();

            while ($weightToProcess > 0) {
                if (!$currentIncome instanceof DocumentIncomeIngredientEntity) {
                    throw new WriteOffLogicalException($this->translator->translate('not enough incomes found for ingredient')
                        . ' ' . $ingredient->getName());
                }

                $currentIncomeResidue = $currentIncome->getResidue();
                $incomeUsedWeight = $currentIncome->getWeight() - $currentIncomeResidue;

                if ($incomeUsedWeight >= $weightToProcess) {
                    $refund = $weightToProcess;
                    $weightToProcess = 0;
                } else {
                    $weightToProcess -= $incomeUsedWeight;
                    $refund = $incomeUsedWeight;
                }

                $currentIncome->setResidue($currentIncomeResidue + $refund);
                $this->getDocumentIncomeIngredientRepository()->save($currentIncome);

                $currentIncome = $availableIncomes->next();
            }
        }

        // if current movement is refund to storage then we must decrease residues
        if ($movementIngredient->getDocument()->getType()->getId() === DocumentMovementType::RETURN_ID) {
            $weightToProcess = abs($weightToProcess);
            $availableIncomes = $this->getDocumentIncomeIngredientRepository()
                ->findAvailableIncomesByIngredient($ingredient);

            /** @var DocumentIncomeIngredientEntity $currentIncome */
            $currentIncome = $availableIncomes->first();

            while ($weightToProcess > 0) {
                if (!$currentIncome instanceof DocumentIncomeIngredientEntity) {
                    throw new WriteOffLogicalException($this->translator->translate('not enough residues found for ingredient')
                        . ' ' . $ingredient->getName());
                }

                $currentIncomeResidue = $currentIncome->getResidue();

                if ($currentIncomeResidue >= $weightToProcess) {
                    $writeOffWeight = $weightToProcess;
                    $weightToProcess = 0;
                } else {
                    $weightToProcess -= $currentIncomeResidue;
                    $writeOffWeight = $currentIncomeResidue;
                }

                $currentIncome->setResidue($currentIncomeResidue - $writeOffWeight);
                $this->getDocumentIncomeIngredientRepository()->save($currentIncome);

                $currentIncome = $availableIncomes->next();
            }
        }

    }

    /**
     * @return DocumentIncomeIngredientRepository
     */
    private function getDocumentIncomeIngredientRepository()
    {
        return $this->em->getRepository(DocumentIncomeIngredientEntity::class);
    }

    /**
     * @return DocumentWriteOffRepository
     */
    private function getDocumentWriteOffRepository()
    {
        return $this->em->getRepository(DocumentWriteOffEntity::class);
    }

    /**
     * @return DocumentMovementIngredientRepository
     */
    private function getDocumentMovementIngredientRepository()
    {
        return $this->em->getRepository(DocumentMovementIngredientEntity::class);
    }
}
