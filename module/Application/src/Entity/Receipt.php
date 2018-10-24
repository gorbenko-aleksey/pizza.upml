<?php

namespace Application\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use App\Entity;

/**
 * Receipt
 *
 * @ORM\Table(name="receipt")
 * @ORM\Entity(repositoryClass="Application\Repository\Receipt")
 */
class Receipt extends Entity\AbstractEntity implements Property\CreatorInterface, Property\ChangerInterface
{
    use Entity\Property\Id;
    use Property\Creator;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="product_weight", type="float")
     */
    private $productWeight;

    /**
     * @var Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ReceiptIngredientWeight", mappedBy="receipt", cascade={"persist"}, orphanRemoval=true)
     */
    private $receiptIngredientWeights;

    public function __construct()
    {
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
     * @return float
     */
    public function getProductWeight()
    {
        return $this->productWeight;
    }

    /**
     * @param float $productWeight
     *
     * @return $this
     */
    public function setProductWeight($productWeight)
    {
        $this->productWeight = (float) $productWeight;

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
     * @param ReceiptIngredientWeight $receiptIngredientWeight
     *
     * @return $this
     */
    public function addReceiptIngredientWeight(ReceiptIngredientWeight $receiptIngredientWeight)
    {
        $this->receiptIngredientWeights->add($receiptIngredientWeight);

        $receiptIngredientWeight->setReceipt($this);

        return $this;
    }

    /**
     * @param ReceiptIngredientWeight $receiptIngredientWeight
     *
     * @return $this
     */
    public function removeReceiptIngredientWeight(ReceiptIngredientWeight $receiptIngredientWeight)
    {
        $this->receiptIngredientWeights->removeElement($receiptIngredientWeight);

        return $this;
    }

    /**
     * @param Collections\Collection $receiptIngredientWeights
     *
     * @return $this
     */
    public function addReceiptIngredientWeights(Collections\Collection $receiptIngredientWeights)
    {
        foreach ($receiptIngredientWeights as $receiptIngredientWeight) {
            $this->addReceiptIngredientWeight($receiptIngredientWeight);
        }

        return $this;
    }

    /**
     * @param Collections\Collection $receiptIngredientWeights
     *
     * @return $this
     */
    public function removeReceiptIngredientWeights(Collections\Collection $receiptIngredientWeights)
    {
        foreach ($receiptIngredientWeights as $receiptIngredientWeight) {
            $this->removeReceiptIngredientWeight($receiptIngredientWeight);
        }

        return $this;
    }
}
