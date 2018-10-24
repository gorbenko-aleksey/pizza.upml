<?php

namespace Application\Controller\Plugin;

use Application\Entity\Page as PageEntity;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class PageDriverAbout extends AbstractPlugin
{
    /**
     * @return bool
     */
    public function isAjaxAccepted()
    {
        return true;
    }

    /**
     * @param PageEntity $page
     *
     * @return ViewModel
     */
    public function action(PageEntity $page)
    {
        $view = new ViewModel(['page' => $page]);
        $view->setTemplate('application/page/driver/about/action');

        return $view;
    }

    /**
     * @return JsonModel
     */
    public function ajaxAction()
    {
        // @todo
        return new JsonModel();
    }
}
