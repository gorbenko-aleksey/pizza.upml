<?php

namespace AppAdmin\Form\Seo;

use Zend\Form;
use AppAdmin\Form\AbstractFilter;
use Zend\Filter as ZendFilter;
use Zend\InputFilter as ZendInputFilter;
use Application\Entity\Seo as SeoEntity;

class Filter extends AbstractFilter
{
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
            'name' => "{$this->prefix}title",
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Title'),
            ],
            'attributes' => [
                'placeholder' => _('Title'),
                'maxlength' => 200,
            ],
        ]);

        $this->add([
            'name' => "{$this->prefix}keywords",
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Keywords'),
            ],
            'attributes' => [
                'placeholder' => _('Keywords'),
                'maxlength' => 200,
            ],
        ]);

        $this->add([
            'name' => "{$this->prefix}description",
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Description'),
            ],
            'attributes' => [
                'placeholder' => _('Description'),
                'maxlength' => 200,
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
            'required' => false,
        ]);

        $inputFilter->add([
            'name' => "{$this->prefix}title",
            'required' => false,
            'filters' => [
                ['name' => ZendFilter\StringTrim::class],
            ],
        ]);

        $inputFilter->add([
            'name' => "{$this->prefix}description",
            'required' => false,
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
            SeoEntity::STATUS_ACTIVE => _('Enabled'),
            SeoEntity::STATUS_NOT_ACTIVE => _('Disabled'),
        ];
    }
}
