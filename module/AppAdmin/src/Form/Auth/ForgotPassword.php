<?php

namespace AppAdmin\Form\Auth;

use App\Form\AbstractForm;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;
use Zend\Validator;

class ForgotPassword extends AbstractForm
{
    /**
     * ForgotPassword constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
    }

    public function init()
    {
        $this->add([
            'name' => 'email',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Email'),
            ],
            'attributes' => [
                'placeholder' => _('Email'),
            ],
        ]);

        $this->add([
            'name' => 'send',
            'type' => Form\Element\Button::class,
            'options' => [
                'label' => _('Send'),
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
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
                ['name' => Filter\StripTags::class],
            ],
            'validators' => [
                [
                    'name' => Validator\EmailAddress::class,
                    'break_chain_on_failure' => true,
                ],
            ],
        ]);

        return $inputFilter;
    }
}
