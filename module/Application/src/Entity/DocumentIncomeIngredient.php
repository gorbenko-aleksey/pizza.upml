<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity;

/**
 * DocumentIncomeIngredient
 *
 * @ORM\Table(name="document_income_ingredient")
 * @ORM\Entity(repositoryClass="Application\Repository\DocumentIncomeIngredient")
 */
class DocumentIncomeIngredient extends Entity\AbstractEntity
{
    use Entity\Property\Id;

    /**
     * @var Ingredient
     *
     * @ORM\ManyToOne(targetEntity="Ingredient", inversedBy="DocumentIncomeIngredient")
     * @ORM\JoinColumn(name="ingredient_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $ingredient;

    /**
     * @var float
     *
     * @ORM\Column(name="weight", type="float")
     */
    private $weight;

    /**
     * @var DocumentIncome
     *
     * @ORM\ManyToOne(targetEntity="DocumentIncome", inversedBy="ingredients")
     */
    private $document;

    /**
     * @var float
     *
     * @ORM\Column(name="residue", type="float")
     */
    private $residue;

    /**
     * @return Ingredient
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * @param Ingredient $ingredient
     *
     * @return $this
     */
    public function setIngredient($ingredient)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     *
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return DocumentIncome
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param DocumentIncome $document
     *
     * @return $this
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * @return float
     */
    public function getResidue()
    {
        return $this->residue;
    }

    /**
     * @param float $residue
     *
     * @return $this
     */
    public function setResidue($residue)
    {
        $this->residue = $residue;

        return $this;
    }
}
