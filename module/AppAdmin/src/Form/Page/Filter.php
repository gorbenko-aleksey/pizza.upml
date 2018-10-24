<?php

namespace AppAdmin\Form\Page;

use AppAdmin\Form\AbstractFilter;
use Zend\Form;
use Zend\Filter as ZendFilter;
use Zend\InputFilter as ZendInputFilter;
use Application\Entity\Page as PageEntity;

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
            'name' => "{$this->prefix}status",
            'type' => Form\Element\Select::class,
            'options' => [
                'label' => _('Status'),
            ],
            'attributes' => [
                'placeholder' => _('Status'),
                'options' => $this->prepareAndGetStatuses(),

            ],
        ]);

        $this->add([
            'name' => "{$this->prefix}name",
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Name'),
            ],
            'attributes' => [
                'placeholder' => _('Name'),
            ],
        ]);

        $this->add([
            'name' => "{$this->prefix}code",
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Code'),
            ],
            'attributes' => [
                'placeholder' => _('Code'),
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
            'name' => "{$this->prefix}status",
        ]);

        $inputFilter->add([
            'name' => "{$this->prefix}name",
            'filters' => [
                ['name' => ZendFilter\StringTrim::class],
            ],
        ]);

        $inputFilter->add([
            'name' => "{$this->prefix}code",
            'filters' => [
                ['name' => ZendFilter\StringTrim::class],
            ],
        ]);
    }

    /**
     * Prepare and get statuses
     *
     * @return array
     */
    protected function prepareAndGetStatuses()
    {
        return [
            '' => _('All'),
            PageEntity::STATUS_ACTIVE => _('Enabled'),
            PageEntity::STATUS_NOT_ACTIVE => _('Disabled'),
        ];
    }

}
