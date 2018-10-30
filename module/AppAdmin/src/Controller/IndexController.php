<?php

namespace AppAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Order as OrderEntity;
use Application\Entity\OrderProduct as OrderProductEntity;
use Application\Repository\Order as OrderRepository;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
{
    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->orderRepository = $em->getRepository(OrderEntity::class);
    }

    public function indexAction()
    {
        $popularProducts = [];
        /* @var OrderEntity $order */
        foreach ($this->orderRepository->findAll() as $order) {
            /* @var OrderProductEntity $product */
            foreach ($order->getProducts() as $product) {
                $productName = $product->getProductParams()['name'];
                if (!isset($popularProducts[$productName])) {
                    $popularProducts[$productName] = 0;
                }
                $popularProducts[$productName] += $product->getQuantity();
            }
        }

        return new ViewModel(['popularProducts' => $popularProducts]);
    }
}
