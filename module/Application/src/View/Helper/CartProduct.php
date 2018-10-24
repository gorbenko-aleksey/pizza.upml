<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\OrderProduct as OrderProductEntity;

class CartProduct extends AbstractHelper
{
    /**
     * @var OrderProductEntity $product
     *
     * @return string
     */
    public function __invoke(OrderProductEntity $product)
    {
        return $this->getView()->partial(
            'application/helper/cart-product',
            ['product' => $product]
        );
    }
}
