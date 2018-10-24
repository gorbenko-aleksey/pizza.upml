<?php

namespace AppAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SortingData extends AbstractHelper
{
    /**
     * For sorting rules
     *
     * @param int $id
     * @param int $currentPosition
     * @param string $routeName
     * @param array $params
     *
     * @return array
     */
    public function __invoke($id, $currentPosition, $routeName, $params = [])
    {
        $view = $this->getView();

        $arrFirst = ['id' => $id, 'position' => '0', 'params' => $params] ;
        $arrFirst = array_merge($arrFirst, $params);
        $hrefFirst = $view->urlWithParams($routeName . '/change-position', $arrFirst);

        $arrPrev = ['id' => $id, 'position' => $currentPosition - 1, 'params' => $params] ;
        $arrPrev = array_merge($arrPrev, $params);
        $hrefPrev = $view->urlWithParams($routeName . '/change-position', $arrPrev);

        $arrNext = ['id' => $id, 'position' => $currentPosition + 1, 'params' => $params] ;
        $arrNext = array_merge($arrNext, $params);
        $hrefNext = $view->urlWithParams($routeName . '/change-position', $arrNext);

        $arrLast = ['id' => $id, 'position' => '-1', 'params' => $params] ;
        $arrLast = array_merge($arrLast, $params);
        $hrefLast = $view->urlWithParams($routeName . '/change-position', $arrLast);

        return $view->render('app-admin/helper/sorting-data', [
            'hrefFirst' => $hrefFirst,
            'hrefPrev' => $hrefPrev,
            'hrefNext' => $hrefNext,
            'hrefLast' => $hrefLast,
        ]);
    }

}