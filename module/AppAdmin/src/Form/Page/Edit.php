<?php

namespace AppAdmin\Form\Page;

use App\Entity\EntityCodeInterface;
use AppAdmin\Form\AbstractEdit;
use Application\Entity\Page as PageEntity;
use Zend\Form;
use Zend\InputFilter;
use Zend\Validator;

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
                'options' => [
                    PageEntity::STATUS_NOT_ACTIVE => _('Disabled'),
                    PageEntity::STATUS_ACTIVE => _('Enabled'),
                ],
            ],
            'attributes' => [
                'placeholder' => _('Status'),
            ],
        ]);

        $this->add([
            'name' => 'name',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Name'),
            ],
            'attributes' => [
                'placeholder' => _('Name'),
                'maxlength' => 200,
                'size' => 90
            ],
        ]);

        $this->add([
            'name' => 'code',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Code'),
            ],
            'attributes' => [
                'placeholder' => _('Code'),
                'maxlength' => 200,
                'size' => 90
            ],
        ]);

        $this->add([
            'name' => 'driver',
            'type' => Form\Element\Select::class,
            'options' => [
                'label' => _('Driver'),
                'options' => $this->prepareAndGetDrivers()
            ],
            'attributes' => [
                'placeholder' => _('Driver'),
            ],
        ]);

        $this->add([
            'name' => 'short_description',
            'type' => 'Textarea',
            'options' => [
                'label' => _('Brief'),
            ],
            'attributes' => [
                'placeholder' => _('Brief description'),
                'cols' => 90,
                'rows' => 4,
            ],
        ]);

        $this->add([
            'name' => 'full_description',
            'type' => 'Textarea',
            'options' => [
                'label' => _('Full'),
            ],
            'attributes' => [
                'placeholder' => _('Full description'),
                'cols' => 90,
                'rows' => 4,
                'id' => 'full_description',
            ],
        ]);

        $this->add(
            [
                'name' => 'html_title',
                'type' => Form\Element\Text::class,
                'options' => [
                    'label' => _('Html title'),
                ],
                'attributes' => [
                    'placeholder' => _('Html title'),
                    'maxlength' => 1000,
                    'size' => 90
                ],
            ]
        );

        $this->add(
            [
                'name' => 'meta_keywords',
                'type' => 'Textarea',
                'options' => [
                    'label' => _('Meta keywords'),
                ],
                'attributes' => [
                    'placeholder' => _('Meta keywords'),
                    'maxlength' => 3000,
                    'cols' => 90,
                    'rows' => 4,
                ],
            ]
        );

        $this->add(
            [
                'name' => 'meta_description',
                'type' => 'Textarea',
                'options' => [
                    'label' => _('Meta description'),
                ],
                'attributes' => [
                    'placeholder' => _('Meta description'),
                    'maxlength' => 5000,
                    'cols' => 90,
                    'rows' => 4,
                ],
            ]
        );

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
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 0,
                        'max' => 200
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'code',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 0,
                        'max' => 200
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'short_description',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
        ]);

        $inputFilter->add([
            'name' => 'driver',
            'required' => false,
        ]);

        $inputFilter->add([
            'name' => 'full_description',
            'required' => false,
        ]);

        $inputFilter->add([
            'name' => 'html_title',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 1000
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'meta_keywords',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 3000
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'meta_description',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 5000
                    ],
                ],
            ],
        ]);
    }

    /**
     * Prepare and get drivers list
     *
     * @return array
     */
    protected function prepareAndGetDrivers()
    {
        return array_merge(
            [_('Select driver')],
            PageEntity::DRIVERS
        );
    }

    /**
     * @param array|\ArrayAccess|\Traversable $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $group = array_keys($this->getElements());

        if (is_object($this->object) &&
            $this->object->getId() &&
            $this->object->getCode() == $data['code']
        ) {
            unset($group[array_search('code', $group)]);
        } else if ($data['code'] === '' && $data['name'] !== '') {
            $data['code'] = $data['name'];
        } else {
            $this->getInputFilter()->get('code')->getValidatorChain()->attach(new Validator\Regex([
                'pattern' => EntityCodeInterface::PATTERN,
                'messages' => [
                    Validator\Regex::NOT_MATCH => _('Illegal characters used'),
                ],
            ]));
        }

        $this->setValidationGroup($group);

        parent::setData($data);

        return $this;
    }
}
