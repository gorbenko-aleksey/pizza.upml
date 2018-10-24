<?php

namespace AppAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use App\Form\FormInterface;

class RenderTabsForm extends AbstractHelper
{
    /**
     * Render form with tabs
     *
     * @param FormInterface $form
     * @param array $tabs
     *
     * @return string
     */
    public function __invoke(FormInterface $form, array $tabs)
    {
        $buttons = $inputs = [];

        foreach ($form->getElements() as $element){
            if (in_array(get_class($element), ['Zend\Form\Element\Button', 'Zend\Form\Element\Submit'])) {
                $buttons[] = $element;
            } else {
                $inputs[] = $element;
            }
        }

        return $this->getView()->render('app-admin/helper/render-tabs-form', [
            'form' => $form,
            'tabs' => $tabs,
            'inputs' => $inputs,
            'buttons' => $buttons,
            'invalidElementsNames' => array_keys($form->getInputFilter()->getMessages()),
        ]);
    }
}
