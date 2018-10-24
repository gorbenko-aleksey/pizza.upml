<?php

namespace AppAdmin\Form\WhiteIp;

use AppAdmin\Form\AbstractEdit;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;
use App\Form\Validator\IpWithMask;

class Edit extends AbstractEdit
{
    public function __construct()
    {
        parent::__construct('edit');

        $this->setAttribute('method', 'post');
    }

    public function init()
    {
        parent::init();

        $this->add([
            'name' => 'ip',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Ip'),
            ],
            'attributes' => [
                'placeholder' => _('Ip'),
            ],
        ]);

        $this->add([
            'name' => 'comment',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Comment'),
            ],
            'attributes' => [
                'placeholder' => _('Comment'),
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
            'name' => 'ip',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['min' => '1', 'max' => '15'],
                ],
                [
                    'name' => IpWithMask::class,
                    'break_chain_on_failure' => true,
                ]
            ],
        ]);

        $inputFilter->add([
            'name' => 'comment',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['min' => '1', 'max' => '500'],
                ]
            ],
        ]);
    }
}
