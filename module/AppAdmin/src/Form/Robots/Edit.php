<?php

namespace AppAdmin\Form\Robots;

use AppAdmin\Form\AbstractEdit;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;

class Edit extends AbstractEdit
{
    public function init()
    {
        parent::init();

        $this->add([
            'name' => 'body',
            'type' => Form\Element\Textarea::class,
            'options' => [
                'label' => _('Robots.txt'),

            ],
            'attributes' => [
                'id' => 'body',
            ],
        ]);

        $this->initInputFilters();
    }

    /**
     * Init validators and filters
     */
    protected function initInputFilters()
    {
        $inputFilter = new InputFilter\InputFilter();

        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'body',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);
    }

}
