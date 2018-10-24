<?php

namespace AppAdmin\Form\Auth;

use App\Form\AbstractForm;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;
use Zend\ServiceManager\ServiceManager;
use Zend\Validator;

class ForgotPasswordConfirm extends AbstractForm
{
    /**
     * ForgotPasswordConfirm constructor.
     */
    public function __construct()
    {
        parent::__construct('forgot_password_confirm');
        $this->setAttribute('method', 'post');
    }

    public function init()
    {
        $this->add([
            'name' => 'password',
            'type' => Form\Element\Password::class,
            'options' => [
                'label' => _('New password'),
            ],
            'attributes' => [
                'placeholder' => _('New password'),
            ],
        ]);

        $this->add([
            'name' => 'password_repeat',
            'type' => Form\Element\Password::class,
            'options' => [
                'label' => _('Re-type new password'),
            ],
            'attributes' => [
                'placeholder' => _('Re-type new password'),
            ],
        ]);

        $this->add([
            'name' => 'save',
            'type' => Form\Element\Button::class,
            'options' => [
                'label' => _('Save'),
            ],
            'attributes' => [
                'type' => 'submit',
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
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['min' => 6, 'max' => 255],
                ]
            ],
        ]);

        $inputFilter->add([
            'name' => 'password_repeat',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => Validator\Identical::class,
                    'break_chain_on_failure' => true,
                    'options' => ['token' => 'password'],
                ],
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['min' => 6, 'max' => 255],
                ]
            ],
        ]);

        return $inputFilter;
    }
}
