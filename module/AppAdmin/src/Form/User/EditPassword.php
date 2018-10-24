<?php

namespace AppAdmin\Form\User;

use Zend\Form;
use Zend\Filter;
use Zend\InputFilter;
use AppAdmin\Form\AbstractEdit;

class EditPassword extends AbstractEdit
{
    public function __construct()
    {
        parent::__construct('edit-password');
        $this->setAttribute('method', 'post');
    }

    public function init()
    {
        parent::init();

        $this->add([
            'name' => 'password',
            'type' => Form\Element\Password::class,
            'options' => [
                'label' => _('Password'),
            ],
            'attributes' => [
                'placeholder' => _('Password'),
                'maxlength' => 255,
            ],
        ]);

        $this->add([
            'name' => 'password_confirm',
            'type' => Form\Element\Password::class,
            'options' => [
                'label' => _('Confirm password'),
            ],
            'attributes' => [
                'placeholder' => _('Confirm password'),
                'maxlength' => 255,
            ],
        ]);

        $this->initInputFilters();
    }

    /**
     * Clear form fields
     *
     * @return $this
     */
    public function clearFields()
    {
        $this->get('password')->setValue('');
        $this->get('password_confirm')->setValue('');

        return $this;
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
            'name' => 'password_confirm',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'Identical',
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
    }
}
