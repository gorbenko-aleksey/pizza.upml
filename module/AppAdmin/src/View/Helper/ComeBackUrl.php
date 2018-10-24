<?php

namespace AppAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Http\PhpEnvironment\Request as Request;
use Zend\Router\Http\TreeRouteStack as Router;
use Application\Service\ComeBackUrlCreator;

class ComeBackUrl extends AbstractHelper
{
    /**
     * @var ComeBackUrlCreator
     */
    protected $comeBackUrlCreator;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Router
     */
    protected $router;

    /**
     * ComeBackUrl constructor.
     *
     * @param ComeBackUrlCreator $comeBackUrlCreator
     * @param Request $request
     * @param Router $router
     */
    public function __construct(ComeBackUrlCreator $comeBackUrlCreator, Request $request, Router $router)
    {
        $this->comeBackUrlCreator = $comeBackUrlCreator;
        $this->request = $request;
        $this->router = $router;
    }

    /**
     * Return link to previous page
     *
     * @return string
     */
    public function __invoke()
    {
        $params = $this->request->getQuery()->toArray();
        $url = $this->comeBackUrlCreator->getComeBackUrl($params);

        if (!$url) {
            $routePath = explode('/', $this->router->match($this->request)->getMatchedRouteName());

            array_pop($routePath);

            if (!empty($routePath)) {
                $url = $this->view->url(implode('/', $routePath));
            }
        }

        return $url;
    }
}
