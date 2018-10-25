<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections;
use App\Entity;

/**
 * DocumentIncome
 *
 * @ORM\Table(name="document_income")
 * @ORM\Entity
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
     * @ORM\OneToMany(targetEntity="DocumentIncomeIngredient", mappedBy="DocumentIncome", cascade={"persist"}, orphanRemoval=true)
     */
    private $ingredients;

    /**
     * DocumentIncome constructor.
     */
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
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;
    }
}
