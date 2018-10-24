<?php

namespace AppAdmin\Form\User;

use AppAdmin\Form\AbstractFilter;
use Zend\Form;
use Zend\Filter as ZendFilter;
use Zend\InputFilter as ZendInputFilter;
use Application\Entity\UserRole as UserRoleEntity;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Form\Element\ObjectSelect as DoctrineSelect;

class Filter extends AbstractFilter
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Edit constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;

        parent::__construct('filter');
    }

    public function init()
    {
        $this->add([
            'name' => "{$this->prefix}email",
            'type' => Form\Element\Email::class,
            'options' => [
                'label' => _('Email'),

            ],
            'attributes' => [
                'placeholder' => _('Email'),
                'maxlength' => $this->defaultMaxLength,
            ],
        ]);

        $this->add([
            'name' => "{$this->prefix}first_name",
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('First name'),
            ],
            'attributes' => [
                'placeholder' => _('First name'),
                'maxlength' => $this->defaultMaxLength,
            ],
        ]);

        $this->add([
            'name' => "{$this->prefix}last_name",
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Last name'),
            ],
            'attributes' => [
                'placeholder' => _('Last name'),
                'maxlength' => $this->defaultMaxLength,
            ],
        ]);

        $this->add([
            'name' => "{$this->prefix}roles",
            'type' => DoctrineSelect::class,
            'options' => [
                'label' => _('Role'),
                'object_manager' => $this->em,
                'target_class' => UserRoleEntity::class,
                'property' => 'name',
                'is_method' => true,
                'display_empty_item' => true,
                'empty_item_label' => _('All'),
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [
                            'id' => [
                                UserRoleEntity::GUEST,
                                UserRoleEntity::USER,
                                UserRoleEntity::OPERATOR,
                                UserRoleEntity::COOK,
                                UserRoleEntity::DRIVER,
                                UserRoleEntity::ADMIN,
                            ],
                        ],
                    ],
                ],
            ],
            'attributes' => [
                'data-placeholder' => _('Select role'),
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
            'name' => "{$this->prefix}email",
            'required' => false,
            'filters' => [
                ['name' => ZendFilter\StringTrim::class],
            ],
        ]);

        $inputFilter->add([
            'name' => "{$this->prefix}first_name",
            'required' => false,
            'filters' => [
                ['name' => ZendFilter\StringTrim::class],
            ],
        ]);

        $inputFilter->add([
            'name' => "{$this->prefix}last_name",
            'required' => false,
            'filters' => [
                ['name' => ZendFilter\StringTrim::class],
            ],
        ]);

        $inputFilter->add([
            'name' => "{$this->prefix}roles",
            'required' => false,
        ]);
    }
}
