<?php

namespace AppAdmin\Form\WhiteIp;

use Zend\Form;
use Zend\ServiceManager\ServiceManager;
use AppAdmin\Form\AbstractFilter;
use Zend\Form\Fieldset;
use Zend\Filter as ZendFilter;
use Zend\InputFilter as ZendInputFilter;

class Filter extends AbstractFilter
{
    /**
     * Filter constructor.
     */
    public function __construct()
    {
        parent::__construct('filter');
    }

    public function init()
    {
        $this->add([
            'name' => "{$this->prefix}ip",
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Ip'),
            ],
            'attributes' => [
                'placeholder' => _('Ip'),
                'maxlength' => $this->defaultMaxLength,
            ],
        ]);

        $this->add([
            'name' => "{$this->prefix}comment",
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Comment'),
            ],
            'attributes' => [
                'placeholder' => _('Comment'),
                'maxlength' => $this->defaultMaxLength,
            ],
        ]);

        $this->initInputFilters();
    }

    /**
     * Init validators and filters
     */
    protected function initInputFilters()
    {
        $inputFilter = new ZendInputFilter\InputFilter();

        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => "{$this->prefix}ip",
            'required' => false,
            'filters' => [
                ['name' => ZendFilter\StringTrim::class],
            ],
        ]);

        $inputFilter->add([
            'name' => "{$this->prefix}comment",
            'required' => false,
            'filters' => [
                ['name' => ZendFilter\StringTrim::class],
            ],
        ]);
    }
}
