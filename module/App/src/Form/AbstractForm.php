<?php

namespace App\Form;

use Zend\Form\Form;
use Zend\Stdlib\InitializableInterface;

abstract class AbstractForm extends Form implements FormInterface, InitializableInterface
{
    /**
     * Form messages
     *
     * @var array
     */
    protected $formMessages = [];

    /**
     * Get form messages
     *
     * @return array
     */
    public function getFormMessages()
    {
        return $this->formMessages;
    }
}
