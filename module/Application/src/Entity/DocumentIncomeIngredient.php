<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity;

/**
 * DocumentIncomeIngredient
 *
 * @ORM\Table(name="document_income_ingredient")
 * @ORM\Entity
 */
class DocumentIncomeIngredient extends Entity\AbstractEntity implements Property\CreatorInterface, Property\ChangerInterface
{
    use Entity\Property\Id;
    use Property\Creator;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * @var Ingredient
     *
     * @ORM\ManyToOne(targetEntity="Ingredient", inversedBy="DocumentIncomeIngredient")
     */
    private $ingredient;

    /**
     * @var DocumentIncome
     *
     * @ORM\ManyToOne(targetEntity="DocumentIncome", inversedBy="DocumentIncomeIngredient")
     */
    private $document;

    /**
     * @return DocumentIncome
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param DocumentIncome $document
     */
    public function setDocument($document)
    {
        $this->document = $document;
    }

    /**
     * @var float
     *
     * @ORM\Column(name="weight", type="float")
     */
    private $weight;

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
     */
    public function setIngredient($ingredient)
    {
        $this->ingredient = $ingredient;
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
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
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
     */
    public function setResidue($residue)
    {
        $this->residue = $residue;
    }
}
