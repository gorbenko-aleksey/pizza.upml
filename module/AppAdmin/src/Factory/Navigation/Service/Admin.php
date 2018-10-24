<?php

namespace AppAdmin\Factory\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

class Admin extends DefaultNavigationFactory
{
    /**
     * @inheritdoc
     */
    protected function getName()
    {
        return 'admin';
    }
}
