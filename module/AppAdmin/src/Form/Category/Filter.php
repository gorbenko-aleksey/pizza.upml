<?php

namespace AppAdmin\Form\Category;

use Zend\Form;
use AppAdmin\Form\AbstractFilter;
use Application\Entity\Category;

class Filter extends AbstractFilter
{

    public function __construct()
    {
        parent::__construct('filter');
    }

    public function init()
    {
        parent::init();

        $this->add(
            [
                'name'    => "{$this->prefix}status",
                'type'    => Form\Element\Select::class,
                'options' => [
                    'label'        => _('Status'),
                    'empty_option' => _('All'),
                    'options'      => [
                        Category::STATUS_ACTIVE     => _('Enabled'),
                        Category::STATUS_NOT_ACTIVE => _('Disabled'),
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => "{$this->prefix}name",
                'type'       => Form\Element\Text::class,
                'options'    => [
                    'label' => _('Name'),
                ],
                'attributes' => [
                    'placeholder' => _('Name'),
                    'maxlength'   => $this->defaultMaxLength,
                ],
            ]
        );

        $this->add(
            [
                'name'       => "{$this->prefix}code",
                'type'       => Form\Element\Text::class,
                'options'    => [
                    'label' => _('Code'),
                ],
                'attributes' => [
                    'placeholder' => _('Code'),
                    'maxlength'   => $this->defaultMaxLength,
                ],
            ]
        );
    }
}
