<?php

namespace AppAdmin\View\Helper;

use AppAdmin\Form\AbstractFilter;
use Zend\View\Helper\AbstractHelper;
use Zend\Form\Element;

class RenderFilterForm extends AbstractHelper
{
    /**
     * Render filter form
     *
     * @param AbstractFilter $form
     * @param int $rowsFold можно передавать только 1, 2, 3, 4, 6, 12
     *
     * @return string
     */
    public function __invoke(AbstractFilter $form, $rowsFold = 3)
    {
        $rows = $inputs = $hidden = [];

        foreach ($form->getElements() as $element){
            if (get_class($element) === 'Zend\Form\Element\Hidden') {
                $hidden[] = $element;
            } else if (!in_array(get_class($element), [Element\Button::class, Element\Submit::class])) {
                $inputs[] = $element;
            }
        }

        switch ($rowsFold) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 6:
            case 12:
                $cols = 12 / $rowsFold;
                break;
            default :
                $cols = 4;
        }

        $j = 0;

        foreach ($inputs as $i => $input) {
            if ($i % $rowsFold === 0) {
                $j++;
            }

            $rows[$j][] = $input;
        }

        return $this->getView()->render('app-admin/helper/render-filter-form', [
            'form' => $form,
            'rows' => $rows,
            'cols' => $cols,
            'hidden' => $hidden,
        ]);
    }
}
