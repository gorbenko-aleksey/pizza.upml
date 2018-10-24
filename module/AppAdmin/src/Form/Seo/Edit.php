<?php

namespace AppAdmin\Form\Seo;

use AppAdmin\Form\AbstractEdit;
use Application\Entity\Seo as SeoEntity;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;

class Edit extends AbstractEdit
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('edit');
        $this->setAttribute('method', 'post');
    }

    public function init()
    {
        parent::init();

        $this->add([
            'name' => 'status',
            'type' => Form\Element\Select::class,
            'options' => [
                'label' => _('Status'),
                'options' => $this->prepareAndGetStatuses()
            ],
            'attributes' => [
                'placeholder' => _('Status'),
            ],
        ]);

        $this->add([
            'name' => 'url',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Url pattern'),
            ],
            'attributes' => [
                'placeholder' => _('Url pattern'),
                'id' => 'url_regex',
            ],
        ]);

        $this->add([
            'name' => 'title',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Title'),
            ],
            'attributes' => [
                'placeholder' => _('Title'),
                'size' => 50
            ],
        ]);

        $this->add([
            'name' => 'keywords',
            'type' => Form\Element\Textarea::class,
            'options' => [
                'label' => _('Keywords'),
            ],
            'attributes' => [
                'placeholder' => _('Keywords'),
                'size' => 50
            ],
        ]);

        $this->add([
            'name' => 'description',
            'type' => Form\Element\Textarea::class,
            'options' => [
                'label' => _('Description'),
            ],
            'attributes' => [
                'placeholder' => _('Description'),
                'size' => 50
            ],
        ]);
    }

    /**
     * Init validators and filters
     */
    protected function initInputFilters()
    {
        $inputFilter = new InputFilter\InputFilter();

        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'status',
            'required' => true,
        ]);

        $inputFilter->add([
            'name' => 'url',
            'required' => true,
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['min' => '1', 'max' => '200'],
                ]
            ],
        ]);

        $inputFilter->add([
            'name' => 'title',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['min' => '3', 'max' => '1000'],
                ]
            ],
        ]);

        $inputFilter->add([
            'name' => 'description',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['min' => '3', 'max' => '1000'],
                ]
            ],
        ]);

        $inputFilter->add([
            'name' => 'keywords',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['min' => '3', 'max' => '1000'],
                ]
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
            SeoEntity::STATUS_ACTIVE => _('Enabled'),
            SeoEntity::STATUS_NOT_ACTIVE => _('Disabled'),
        ];
    }
}
