<?php

namespace AppAdmin\Form\Product;

use Zend\Form;
use Zend\Filter;
use Zend\Form\Fieldset;
use Zend\Validator\File;
use Zend\InputFilter\InputFilterProviderInterface;

class FileFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('image_upload');
    }

    public function init()
    {
        $this->add([
            'name'    => 'file',
            'type'    => Form\Element\File::class,
            'options' => [
                'label' => _('Image'),
            ],
        ]);

        $this->add([
            'name'    => 'remove',
            'type'    => Form\Element\Checkbox::class,
            'options' => [
                'label' => _('Remove'),
            ],
        ]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification(): array
    {
        return [
            'file'   => [
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => File\Size::class,
                        'options' => [
                            'break_chain_on_failure' => true,
                            'min'                    => 1,
                            'max'                    => 8,
                        ],
                    ],
                    [
                        'name'    => File\Extension::class,
                        'options' => [
                            'break_chain_on_failure' => true,
                            'extension'              => 'jpeg, jpg, png, gif, ico',
                        ],
                    ],
                    [
                        'name'                   => File\MimeType::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'mimeType' => ['image'],
                            'messages' => [
                                File\MimeType::FALSE_TYPE => 'This file is not image',
                            ],
                        ],
                    ],
                ]
            ],
            'remove' => [
                'required' => false,
            ],
        ];
    }
}
