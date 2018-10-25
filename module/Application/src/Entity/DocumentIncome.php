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

    const STATUS_HELD = 1;

    const STATUS_NOT_HELD = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status = self::STATUS_NOT_HELD;

    /**
     * @var Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="DocumentIncomeIngredient", mappedBy="document", cascade={"persist"}, orphanRemoval=true)
     */
    private $ingredients;

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
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * @param \Application\Entity\DocumentIncomeIngredient $ingredient
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
     * @param \Application\Entity\DocumentIncomeIngredient $ingredient
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

    /**
     * Is document held or not
     *
     * @return bool
     */
    public function isHold()
    {
        return $this->status === self::STATUS_HELD;
    }

    /**
     * Is document can be edited
     *
     * @return bool
     */
    public function isEditable()
    {
        return !$this->isUsed();
    }

    /**
     * Is document can be deleted
     *
     * @return bool
     */
    public function canBeDeleted()
    {
        return $this->getStatus() !== DocumentIncome::STATUS_HELD && $this->isEditable();
    }

    /**
     * @return bool
     */
    public function isUsed()
    {
        $used = false;

        /** @var DocumentIncomeIngredient $ingredient */
        foreach ($this->getIngredients() as $ingredient) {
            if ($ingredient->getWeight() != $ingredient->getResidue()) {
                $used = true;
                break;
            }
        }

        return $used;
    }
}
