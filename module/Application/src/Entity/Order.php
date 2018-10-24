<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections;
use App\Entity;

/**
 * Order
 *
 * @ORM\Table(name="`order`")
 * @ORM\Entity(repositoryClass="Application\Repository\Order")
 */
class Order extends Entity\AbstractEntity
{
    use Entity\Property\Id;
    use Entity\Property\CreatedAt;
    use Entity\Property\ChangedAt;

    const STATUS_DELETED   = 0;
    const STATUS_CREATED   = 1;
    const STATUS_APPROVED  = 2;
    const STATUS_COMPLETED = 3;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var Collection
     * 
     * @ORM\OneToMany(targetEntity="OrderProduct", cascade={"persist"}, orphanRemoval=true, mappedBy="order")
     * @ORM\JoinColumn(name="id", referencedColumnName="order_id")
     */
    private $products;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->products = new Collections\ArrayCollection();
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
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        // @todo
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     *
     * @return $this
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Collections\ArrayCollection $products
     *
     * @return $this
     */
    public function addProducts(Collections\ArrayCollection $products)
    {
        foreach ($products as $product) {
            $this->products->add($product);
        }

        return $this;
    }

    /**
     * @param OrderProduct $product
     *
     * @return $this
     */
    public function addProduct($product)
    {
        $this->products->add($product);

        return $this;
    }

    /**
     * @param Collections\ArrayCollection|null $products
     *
     * @return $this
     */
    public function removeProducts(Collections\ArrayCollection $products = null)
    {
        if ($products) {
            foreach ($products as $product) {
                $this->products->removeElement($product);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeProduct($product)
    {
        $this->products->removeElement($product);

        return $this;
    }
}
