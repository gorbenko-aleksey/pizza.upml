<?php

namespace AppAdmin\View\Helper;

use Zend\Paginator\Paginator;
use Zend\View\Helper\AbstractHelper;

class Pagination extends AbstractHelper
{
    /**
     * Render pagination
     *
     * @param Paginator|null $paginator
     * @param array|null $params
     *
     * @return string
     */
    public function __invoke(Paginator $paginator = null, $params = null)
    {
        return $this->getView()->paginationControl(
            $paginator, null, 'app-admin/helper/pagination', $params
        );
    }
}
