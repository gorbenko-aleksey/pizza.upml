<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;
use Application\Service\Cart as CartService;
use Application\Entity\Product as ProductEntity;
use Application\Repository\Product as ProductRepository;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;

class CartController extends AbstractActionController
{
    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var HelperPluginManager
     */
    protected $viewHelperManager;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @param EntityManager $em
     * @param CartService $cartService
     */
    public function __construct(EntityManager $em, CartService $cartService, ServiceManager $sm)
    {
        $this->productRepository = $em->getRepository(ProductEntity::class);
        $this->viewHelperManager = $sm->get('ViewHelperManager');
        $this->cartService = $cartService;
    }

    public function addAction()
    {
        $product = $this->productRepository->find($this->params()->fromPost('id'));

        if (!($product && $product->getStatus())) {
            return $this->getResponse()->setStatusCode(404);
        }

        $this->cartService->add($product);

        $cartViewHelper = $this->viewHelperManager->get('cart');

        return new JsonModel([
            'html' => $cartViewHelper(),
            'qty' => $this->cartService->getQty(),
        ]);
    }

    public function updateAction()
    {
        if (!($product = $this->productRepository->find($this->params()->fromPost('id')))) {
            return $this->getResponse()->setStatusCode(404);
        }

        $this->cartService->update($product, $this->params()->fromPost('qty'));

        return $this->getResponse();
    }
}
