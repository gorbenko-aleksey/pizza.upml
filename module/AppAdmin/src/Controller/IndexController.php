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
        $orderCountByDays = $orderTotalPriceByDays = $popularProducts = [];
        for ($i = 6; $i >= 0; $i--) {
            $orderCountByDays[date('m-d-Y', strtotime('-' . $i . ' days'))] = 0;
            $orderTotalPriceByDays[date('m-d-Y', strtotime('-' . $i . ' days'))] = 0;
        }

        /* @var OrderEntity $order */
        foreach ($this->orderRepository->findAll() as $order) {
            $orderDate = $order->getCreatedAt()->format('m-d-Y');
            if (in_array($orderDate, array_keys($orderCountByDays))) {
                $orderCountByDays[$orderDate] += 1;
                $orderTotalPriceByDays[$orderDate] += $order->getTotalPrice();
            }
            /* @var OrderProductEntity $product */
            foreach ($order->getProducts() as $product) {
                $productName = $product->getProductParams()['name'];
                if (!isset($popularProducts[$productName])) {
                    $popularProducts[$productName] = 0;
                }
                $popularProducts[$productName] += $product->getQuantity();
            }
        }

        return new ViewModel([
            'orderTotalPriceByDays' => $orderTotalPriceByDays,
            'orderCountByDays'      => $orderCountByDays,
            'popularProducts'       => $popularProducts,
        ]);
    }
}
