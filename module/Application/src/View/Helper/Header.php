<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Service\Cart as CartService;

class Header extends AbstractHelper
{
    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        return $this->getView()->partial(
            'application/helper/header',
            ['cartQty' => $this->cartService->getQty()]
        );
    }
}
