<?php

namespace AppAdmin\Form\User;

use AppAdmin\Form\AbstractEdit;
use DoctrineModule\Form\Element\ObjectSelect as DoctrineSelect;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;
use Application\Entity\User as UserEntity;
use Application\Entity\UserRole as UserRoleEntity;
use Doctrine\ORM\EntityManager;

class Edit extends AbstractEdit
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;

        parent::__construct('edit');
        $this->setAttribute('method', 'post');
    }

    public function init()
    {
        parent::init();

        $this->add([
            'name' => 'email',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Email'),

            ],
            'attributes' => [
                'placeholder' => _('Email'),
                'maxlength' => 255,
            ],
        ]);

        $this->add([
            'name' => 'first_name',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('First name'),
            ],
            'attributes' => [
                'placeholder' => _('First name'),
                'maxlength' => 255,
            ],
        ]);

        $this->add([
            'name' => 'last_name',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Last name'),
            ],
            'attributes' => [
                'placeholder' => _('Last name'),
                'maxlength' => 255,
            ],
        ]);

        $this->add([
            'name' => 'roles',
            'type' => DoctrineSelect::class,
            'options' => [
                'label' => _('Role'),
                'object_manager' => $this->em,
                'target_class' => UserRoleEntity::class,
                'property' => 'name',
                'is_method' => true,
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
                'multiple' => 'multiple',
            ],
        ]);

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
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => [
                        'object_repository' => $this->em->getRepository(UserEntity::class),
                        'fields' => 'email',
                        'messages' => [
                            'objectFound' => 'A user with this email already exists.',
                        ],
                    ],
                ],
                [
                    'name' => \Zend\Validator\StringLength::class,
                    'options' => [
                        'max' => 255,
                    ],
                ]
            ],
        ]);


        $inputFilter->add([
            'name' => 'first_name',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => \Zend\Validator\StringLength::class,
                    'options' => [
                        'max' => 255,
                    ],
                ],
                ['name' => \Zend\I18n\Validator\Alpha::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'last_name',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => \Zend\Validator\StringLength::class,
                    'options' => [
                        'max' => 255,
                    ],
                ],
                ['name' => \Zend\I18n\Validator\Alpha::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'roles',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'status',
            'required' => true,
        ]);
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
            $this->object->getEmail() === $data['email']
        ) {
            unset($group[array_search('email', $group)]);
        }

        $this->setValidationGroup($group);

        return parent::setData($data);
    }

}
