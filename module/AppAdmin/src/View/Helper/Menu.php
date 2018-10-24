<?php

namespace AppAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManager;

class Menu extends AbstractHelper
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * Constructor
     *
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Render menu
     *
     * @return string
     */
    public function __invoke()
    {
        $menu = $this->getView()->navigation('admin-navigation')->menu();
        $menu->setAcl($this->serviceManager->get('Application\Permissions\Acl\Acl'));
        $menu->setRole($this->getView()->identity()->getRoles()->last());
        $menu->setUseAcl()->setPartial('app-admin/helper/menu');

        return $menu->render();
    }
}
