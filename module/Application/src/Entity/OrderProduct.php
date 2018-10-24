<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity;

/**
 * OrderProduct
 *
 * @ORM\Table(name="order_product")
 * @ORM\Entity
 */
class OrderProduct extends Entity\AbstractEntity
{
    use Entity\Property\Id;
    use Entity\Property\CreatedAt;
    use Entity\Property\ChangedAt;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order", cascade={"persist"}, inversedBy="OrderProduct")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $hash;

    /**
     * @var Product
     *
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var array - ['name', ..]
     *
     * @ORM\Column(type="json_array")
     */
    private $productParams;

    /**
     * @var array - ['name', 'description', 'infredients' => ['name', 'weight'], ..]
     *
     * @ORM\Column(type="json_array")
     */
    private $receiptParams;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var double
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     *
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return array - ['name', ..]
     */
    public function getProductParams()
    {
        return $this->productParams;
    }

    /**
     * @param array $productParams - ['name', ..]
     *
     * @return $this
     */
    public function setProductParams($productParams)
    {
        $this->productParams = $productParams;

        return $this;
    }

    /**
     * @return array - ['name', 'description', 'infredients' => ['name', 'weight'], ..]
     */
    public function getReceiptParams()
    {
        return $this->receiptParams;
    }

    /**
     * @param array $receiptParams - ['name', 'description', 'infredients' => ['name', 'weight'], ..]
     *
     * @return $this
     */
    public function setReceiptParams($receiptParams)
    {
        $this->receiptParams = $receiptParams;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get total price
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->getPrice() * $this->getQuantity();
    }
}
