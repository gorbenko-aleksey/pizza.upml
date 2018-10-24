<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Service\Cart as CartService;
use Application\Entity\Order as OrderEntity;
use Application\Repository\Order as OrderRepository;
use Application\Entity\OrderProduct as OrderProductEntity;
use Application\Repository\OrderProduct as OrderProductRepository;

class OrderController extends AbstractActionController
{
    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var OrderRepository 
     */
    protected $orderRepository;

    /**
     * @var OrderProductRepository 
     */
    protected $orderProductRepository;

    /**
     * @param CartService $cartService
     */
    public function __construct(EntityManager $em, CartService $cartService)
    {
        $this->orderProductRepository = $em->getRepository(OrderProductEntity::class);
        $this->orderRepository = $em->getRepository(OrderEntity::class);
        $this->cartService = $cartService;
    }

    public function indexAction()
    {
        if ($this->cartService->getAll()->count() && $this->getRequest()->isPost()) {
            $order = new OrderEntity();
            $order->setStatus(OrderEntity::STATUS_CREATED);
            $order->setPhone($this->params()->fromPost('phone'));
            $order->setAddress($this->params()->fromPost('address'));
            $order->setNotes('test');

            $order->addProducts($this->cartService->getAll());
            foreach ($this->cartService->getAll() as $product) {
                $product->setOrder($order);
            }
            $this->orderRepository->save($order);

            $this->cartService->getAll()->clear();

            return new ViewModel();
        } else {
            return $this->getResponse()->setStatusCode(400);
        }
    }
}
