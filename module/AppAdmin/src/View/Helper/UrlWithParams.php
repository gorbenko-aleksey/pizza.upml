<?php

namespace AppAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Http\PhpEnvironment\Request;

class UrlWithParams extends AbstractHelper
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Add current filters to url
     *
     * @param string  $route
     * @param array   $params
     *
     * @return string
     */
    public function __invoke($route, $params = [])
    {
        $url = $this->getView()->url($route, $params);

        return sprintf(
            "%s?return_url=%s",
            $url,
            urlencode($this->getUrlToRedirect())
        );
    }

    /**
     * Get url for redirect
     *
     * @return string
     */
    protected function getUrlToRedirect()
    {
        return str_replace($this->getView()->serverUrl(), '', $this->request->getUriString());
    }
}
