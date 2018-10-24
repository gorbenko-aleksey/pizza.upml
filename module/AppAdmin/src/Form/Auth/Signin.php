<?php

namespace AppAdmin\Form\Auth;

use Zend\Form;
use Zend\Filter;
use Zend\InputFilter;
use App\Form\AbstractForm;

class Signin extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('signin');
        $this->setAttribute('method', 'post');

        $this->add(
            [
                'name' => 'email',
                'type' => Form\Element\Text::class,
                'options' => [
                    'label' => _('Email'),
                ],
                'attributes' => [
                    'placeholder' => _('Email'),
                ],
            ]
        );

        $this->add(
            [
                'name' => 'password',
                'type' => Form\Element\Password::class,
                'options' => [
                    'label' => _('Password'),
                ],
                'attributes' => [
                    'placeholder' => _('Password'),
                ],
            ]
        );

        $this->add(
            [
                'name' => 'remember_me',
                'type' => Form\Element\Checkbox::class,
                'options' => [
                    'label' => _('Remember me'),
                ],
            ]
        );


        $this->add(
            [
                'name' => 'save',
                'type' => Form\Element\Button::class,
                'options' => [
                    'label' => _('Login'),
                ],
                'attributes' => [
                    'type' => 'submit',
                ],
            ]
        );

        $this->setInputFilter($this->getFilter());
    }

    public function getFilter()
    {
        $factory = new InputFilter\Factory();
        $inputFilter = new InputFilter\InputFilter();

        $inputFilter->add(
            $factory->createInput(
                [
                    'name' => 'email',
                    'required' => true,
                    'filters' => [
                        ['name' => Filter\StringTrim::class],
                    ],
                ]
            )
        );

        $inputFilter->add(
            $factory->createInput(
                [
                    'name' => 'password',
                    'required' => true,
                    'filters' => [
                        ['name' => Filter\StringTrim::class],
                    ],
                ]
            )
        );

        return $inputFilter;
    }
}
