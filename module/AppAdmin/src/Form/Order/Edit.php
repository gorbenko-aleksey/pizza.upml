<?php

namespace AppAdmin\Form\Order;

use AppAdmin\Form\AbstractEdit;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;

class Edit extends AbstractEdit
{
    public function init()
    {
        $this->add(
            [
                'name'    => 'address',
                'type'    => Form\Element\Text::class,
                'options' => [
                    'label' => _('Adress'),

                ],
            ]
        );

        $this->add(
            [
                'name'    => 'phone',
                'type'    => Form\Element\Tel::class,
                'options' => [
                    'label' => _('Phone'),
                ],
            ]
        );

        $this->initInputFilters();
    }

    protected function initInputFilters()
    {
        $inputFilter = new InputFilter\InputFilter();

        $this->setInputFilter($inputFilter);

        $inputFilter->add(
            [
                'name'     => 'address',
                'required' => true,
                'filters'  => [
                    ['name' => Filter\StringTrim::class],
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'     => 'phone',
                'required' => true,
                'filters'  => [
                    ['name' => Filter\StringTrim::class],
                ],
            ]
        );
    }
}
