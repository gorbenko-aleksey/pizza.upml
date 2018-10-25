<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity;

/**
 * DocumentWriteOff
 *
 * @ORM\Table(name="document_write_off")
 * @ORM\Entity(repositoryClass="Application\Repository\DocumentWriteOff")
 */
class DocumentWriteOff extends Entity\AbstractEntity implements Property\CreatorInterface, Property\ChangerInterface
{
    use Entity\Property\Id;
    use Property\Creator;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * @var Ingredient
     *
     * @ORM\ManyToOne(targetEntity="Ingredient", inversedBy="ingredientsWriteOff")
     */
    private $ingredient;

    /**
     * @var float
     *
     * @ORM\Column(name="weight", type="float")
     */
    private $weight;

    /**
     * @var DocumentMovement
     *
     * @ORM\ManyToOne(targetEntity="DocumentMovement", inversedBy="writeOffs")
     */
    private $movement;

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
    public function getMovement()
    {
        return $this->movement;
    }

    /**
     * @param DocumentMovement $movement
     *
     * @return $this
     */
    public function setMovement($movement)
    {
        $this->movement = $movement;

        return $this;
    }
}
