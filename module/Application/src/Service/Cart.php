<?php

namespace Application\Service;

use Application\Entity\Product as ProductEntity;
use Application\Repository\Product as ProductRepository;
use Application\Entity\OrderProduct as OrderProductEntity;
use Zend\Session\Storage\SessionArrayStorage;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;

class Cart
{
    /**
     * @var SessionArrayStorage
     */
    protected $session;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @param EntityManager $em
     * @param ServiceManager $serviceManager
     */
    public function __construct(EntityManager $em, ServiceManager $serviceManager)
    {
        $this->productRepository = $em->getRepository(ProductEntity::class);
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return SessionArrayStorage
     */
    private function session()
    {
        if (is_null($this->session)) {
            $this->session = $this->serviceManager->get('CartContainer');
        }

        if (!$this->session->products) {
            $this->session->products = new ArrayCollection();
        }

        return $this->session;
    }

    /**
     * @param ProductEntity $product
     *
     * @return $this
     */
    public function add(ProductEntity $product)
    {
        $found = false;
        $hash = md5($product->getId() . $product->getName());
        foreach ($this->session()->products as $orderProduct) {
            /* @var $existingProduct OrderProductEntity */
            if ($orderProduct->getHash() == $hash) {
                $newQty = $orderProduct->getQuantity() + 1;
                $orderProduct->setQuantity($newQty);
                $found = true;
                break;
            }
        }

        if (!$found) {
            $orderProduct =  new OrderProductEntity();
            $orderProduct->setPrice($product->getPrice());
            $orderProduct->setProduct($product);
            $orderProduct->setHash($hash);
            $orderProduct->setQuantity(1);
            $orderProduct->setProductParams([
                'name' => $product->getName(),
            ]);
            $b = [
                'name' => $product->getReceipt()->getName(),
                'description' => $product->getReceipt()->getDescription(),
                'product_weight' => $product->getReceipt()->getProductWeight(),
            ];
            foreach ($product->getReceipt()->getReceiptIngredientWeights() as $receiptIngredientWeight) {
                $b['ingredient'][] = [
                    'name' => $receiptIngredientWeight->getIngredient()->getName(),
                    'weight' => $receiptIngredientWeight->getWeight(),
                ];
            }

            $orderProduct->setReceiptParams($b);

            $this->session()->products->add($orderProduct);
        }
    }

    /**
     * @param ProductEntity $product
     * @param int $qty
     *
     * @return $this
     */
    public function update(ProductEntity $product, $qty)
    {
        $hash = md5($product->getId() . $product->getName());
        foreach ($this->session()->products as $orderProduct) {
            /* @var $existingProduct OrderProductEntity */
            if ($orderProduct->getHash() == $hash) {
                $orderProduct->setQuantity($qty);
            }
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        $total = 0;

        foreach ($this->session()->products as $product) {
            $total += $product->getTotalPrice();
        }

        return $total;
    }

    /**
     * @return int
     */
    public function getQty()
    {
        return $this->session()->products->count();
    }

    /**
     * @return ArrayCollection
     */
    public function getAll()
    {
        $this->checkSessionProducts();

        return $this->session()->products;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->session()->products->isEmpty();
    }

    public function checkSessionProducts()
    {
        foreach ($this->session()->products as $product) {
            $originalProduct = $this->productRepository->find($product->getProduct()->getId());

            if (!$originalProduct || $originalProduct->getStatus() == ProductEntity::STATUS_DISABLED) {
                $this->removeByHash($product->getHash()); // todo
                break;
            } else {
                $product->setProduct($originalProduct);
            }
        }
    }
}
