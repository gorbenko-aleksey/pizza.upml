<?php

namespace AppAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormatWeight extends AbstractHelper
{
    /**
     * Format weight
     *
     * @return string
     */
    public function __invoke($weight)
    {
        return $weight / 1000;
    }
}
