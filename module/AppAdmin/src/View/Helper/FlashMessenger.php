<?php

namespace AppAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FlashMessenger extends AbstractHelper
{
    /**
     * Render flash messenges
     *
     * @return string
     */
    public function __invoke()
    {
        return $this->getView()->partial('app-admin/helper/flash-messenger');
    }
}
