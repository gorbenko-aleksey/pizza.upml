<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections;
use App\Entity;

/**
 * DocumentIncome
 *
 * @ORM\Table(name="document_income")
 * @ORM\Entity(repositoryClass="Application\Repository\DocumentIncome")
 */
class DocumentIncome extends Entity\AbstractEntity implements Property\CreatorInterface, Property\ChangerInterface
{
    use Entity\Property\Id;
    use Property\Creator;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="DocumentIncomeIngredient", mappedBy="document", cascade={"persist"}, orphanRemoval=true)
     */
    private $ingredients;

    public function __construct()
    {
        $this->ingredients = new Collections\ArrayCollection();
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
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     */
    public function setIngredients(Collections\Collection $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * @param DocumentIncomeIngredient $ingredient
     *
     * @return $this
     */
    public function addIngredient(DocumentIncomeIngredient $ingredient)
    {
        $this->ingredients->add($ingredient);

        $ingredient->setDocument($this);

        return $this;
    }

    /**
     * @param DocumentIncomeIngredient $ingredient
     *
     * @return $this
     */
    public function removeIngredient(DocumentIncomeIngredient $ingredient)
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
     * @return bool
     */
    public function isUsed()
    {
        $used = false;
        foreach ($this->getIngredients() as $ingredient) {
            if ($ingredient->getWeight() != $ingredient->getResidue()) {
                $used = true;
                break;
            }
        }

        return $used;
    }
}
