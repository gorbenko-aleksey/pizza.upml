<?php

namespace AppAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Copyright extends AbstractHelper
{
    /**
     * Render copyright
     *
     * @return string
     */
    public function __invoke()
    {
        return $this->getView()->partial('app-admin/helper/copyright');
    }
}
