<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Footer extends AbstractHelper
{
    /**
     * @return string
     */
    public function __invoke()
    {
        return $this->getView()->partial('application/helper/footer');
    }
}
