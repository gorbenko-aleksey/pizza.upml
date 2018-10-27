<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections;
use App\Entity;

/**
 * Ingredient
 *
 * @ORM\Table(name="ingredient")
 * @ORM\Entity(repositoryClass="Application\Repository\Ingredient")
 */
class Ingredient extends Entity\AbstractEntity implements Property\CreatorInterface, Property\ChangerInterface
{
    use Entity\Property\Id;
    use Property\Creator;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="DocumentIncomeIngredient", mappedBy="ingredient")
     */
    private $ingredientIncomes;

    /**
     * @var Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ReceiptIngredientWeight", mappedBy="ingredient")
     */
    private $receiptIngredientWeights;

    public function __construct()
    {
        $this->ingredientIncomes = new Collections\ArrayCollection();
        $this->receiptIngredientWeights = new Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collections\Collection
     */
    public function getIngredientIncomes()
    {
        return $this->ingredientIncomes;
    }

    /**
     * @param Collections\Collection $ingredientIncome
     *
     * @return $this
     */
    public function setIngredientIncomes($ingredientIncome)
    {
        $this->ingredientIncomes = $ingredientIncome;

        return $this;
    }

    /**
     * @return Collections\Collection
     */
    public function getReceiptIngredientWeights()
    {
        return $this->receiptIngredientWeights;
    }

    /**
     * @param Collections\Collection $receiptIngredientWeights
     *
     * @return $this
     */
    public function setReceiptIngredientWeights($receiptIngredientWeights)
    {
        $this->receiptIngredientWeights = $receiptIngredientWeights;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUsed()
    {
        return !($this->getIngredientIncomes()->isEmpty() && $this->getReceiptIngredientWeights()->isEmpty());
    }
}
