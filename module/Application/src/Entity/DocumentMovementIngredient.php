<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity;

/**
 * DocumentMovementIngredient
 *
 * @ORM\Table(name="document_movement_ingredient")
 * @ORM\Entity(repositoryClass="Application\Repository\DocumentMovementIngredient")
 */
class DocumentMovementIngredient extends Entity\AbstractEntity
{
    use Entity\Property\Id;

    /**
     * @var Ingredient
     *
     * @ORM\ManyToOne(targetEntity="Ingredient", inversedBy="ingredientsMovement")
     */
    private $ingredient;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @var DocumentMovement
     *
     * @ORM\ManyToOne(targetEntity="DocumentMovement", inversedBy="ingredients")
     */
    private $document;

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
     * @return DocumentMovement
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param DocumentMovement $document
     *
     * @return $this
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }
}
