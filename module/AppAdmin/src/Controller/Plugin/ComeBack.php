<?php

namespace AppAdmin\Controller\Plugin;

use Application\Service\ComeBackUrlCreator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Router\Http\TreeRouteStack as Router;
use Zend\Http\PhpEnvironment\Response;

class ComeBack extends AbstractPlugin
{
    /**
     * @var ComeBackUrlCreator
     */
    protected $comeBackUrlCreator;

    /**
     * @var Router
     */
    protected $router;

    /**
     * ComeBack constructor.
     *
     * @param ComeBackUrlCreator $comeBackUrlCreator
     * @param Router $router
     */
    public function __construct(ComeBackUrlCreator $comeBackUrlCreator, Router $router)
    {
        $this->comeBackUrlCreator = $comeBackUrlCreator;
        $this->router = $router;
    }

    /**
     * Return link to previous page
     *
     * @return Response
     */
    public function __invoke()
    {
        $controller = $this->getController();

        $params = $controller->getRequest()->getQuery()->toArray();
        $url = $this->comeBackUrlCreator->getComeBackUrl($params);

        if (!$url) {
            $routePath = explode('/', $this->router->match($controller->getRequest())->getMatchedRouteName());

            array_pop($routePath);

            if (!empty($routePath)) {
                $url = $this->getController()->url()->fromRoute(implode('/', $routePath));
            }
        }

        return $this->getController()->redirect()->toUrl($url);
    }
}