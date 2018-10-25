<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections;
use App\Entity;

/**
 * DocumentMovement
 *
 * @ORM\Table(name="document_movement")
 * @ORM\Entity(repositoryClass="Application\Repository\DocumentMovement")
 */
class DocumentMovement extends Entity\AbstractEntity implements Property\CreatorInterface, Property\ChangerInterface
{
    use Entity\Property\Id;
    use Property\Creator;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * @var DocumentMovementType
     *
     * @ORM\ManyToOne(targetEntity="DocumentMovementType")
     */
    private $type;
    
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ingredientsMovements")
     */
    private $operator;

    /**
     * @var Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="DocumentMovementIngredient", mappedBy="document", cascade={"persist"}, orphanRemoval=true)
     */
    private $ingredients;

    /**
     * @var Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="DocumentWriteOff", mappedBy="movement", orphanRemoval=true)
     */
    private $writeOffs;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * Document constructor.
     */
    public function __construct()
    {
        $this->ingredients = new Collections\ArrayCollection();
        $this->writeOffs = new Collections\ArrayCollection();
    }

    /**
     * @return DocumentMovementType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param DocumentMovementType $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return User
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param User $operator
     *
     * @return $this
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * @return Collections\Collection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @param Collections\Collection $ingredients
     *
     * @return $this
     */
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * @param \Application\Entity\DocumentMovementIngredient $ingredient
     *
     * @return $this
     */
    public function addIngredient(DocumentMovementIngredient $ingredient)
    {
        $this->ingredients->add($ingredient);

        $ingredient->setDocument($this);

        return $this;
    }

    /**
     * @param \Application\Entity\DocumentMovementIngredient $ingredient
     *
     * @return $this
     */
    public function removeIngredient(DocumentMovementIngredient $ingredient)
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * @param Collections\Collection|null $ingredients
     *
     * @return $this
     */
    public function addIngredients(Collections\Collection $ingredients = null)
    {
        if (!$ingredients) {
            return $this;
        }

        foreach ($ingredients as $ingredient) {
            $this->addIngredient($ingredient);
        }

        return $this;
    }

    /**
     * @param Collections\Collection|null $ingredients
     *
     * @return $this
     */
    public function removeIngredients(Collections\Collection $ingredients = null)
    {
        if (!$ingredients) {
            return $this;
        }

        foreach ($ingredients as $ingredient) {
            $this->removeIngredient($ingredient);
        }

        return $this;
    }

    /**
     * @return Collections\Collection
     */
    public function getWriteOffs()
    {
        return $this->writeOffs;
    }

    /**
     * @param Collections\Collection $writeOffs
     *
     * @return $this
     */
    public function setWriteOffs($writeOffs = null)
    {
        $this->writeOffs = $writeOffs;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
