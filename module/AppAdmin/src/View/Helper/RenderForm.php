<?php

namespace AppAdmin\View\Helper;

use Zend\Form\Element;
use Zend\Form\FormInterface;
use Zend\View\Helper\AbstractHelper;

class RenderForm extends AbstractHelper
{
    /**
     * Render form
     *
     * @param FormInterface $form
     * @param array $fieldSetsScripts
     *
     * @return string
     */
    public function __invoke(FormInterface $form, array $fieldSetsScripts = [])
    {
        $buttons = $inputs = [];

        foreach ($form->getElements() as $element) {
            if (in_array(get_class($element), [Element\Button::class, Element\Submit::class])) {
                $buttons[] = $element;
            } else {
                $inputs[] = $element;
            }
        }

        return $this->getView()->partial(
            'app-admin/helper/render-form', [
                'form' => $form,
                'inputs' => $inputs,
                'buttons' => $buttons,
                'fieldSetsScripts' => $fieldSetsScripts,
            ]
        );
    }
}
