<?php

namespace AppAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Form\FieldsetInterface;

class RenderTableIngredientFieldset extends AbstractHelper
{

    /**
     * Рендерит fieldset ингредиентов в виде строк таблички
     *
     * @param FieldsetInterface|null $fieldset
     *
     * @return string
     */
    public function __invoke(FieldsetInterface $fieldset = null)
    {
        if (!$fieldset) {
            return $this;
        }

        return $this->getView()->render('app-admin/helper/table-ingredient-fieldset', [
            'fieldSet' => $fieldset,
            'parsedFieldsetName' => str_replace([']', '['], '_', $fieldset->getName()),
        ]);
    }

}
