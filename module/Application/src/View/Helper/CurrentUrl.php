<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Http\PhpEnvironment\Request;

class CurrentUrl extends AbstractHelper
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
     * @return string
     */
    public function __invoke()
    {
        return $this->request->getRequestUri();
    }
}
