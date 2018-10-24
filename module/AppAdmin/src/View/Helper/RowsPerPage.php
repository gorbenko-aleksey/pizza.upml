<?php

namespace AppAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Http\PhpEnvironment\Request;

class RowsPerPage extends AbstractHelper
{
    /**
     * @var int
     */
    const LIMIT = 20;

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
        return $this->getView()->partial('app-admin/helper/rows-per-page',
            ['limit' => $this->request->getQuery('limit', self::LIMIT)]
        );
    }
}
