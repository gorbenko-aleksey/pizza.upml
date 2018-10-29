<?php

namespace AppAdmin\Form\Order;

use AppAdmin\Form\AbstractEdit;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Form\Element\ObjectSelect as DoctrineSelect;
use Application\Entity\User as UserEntity;
use Application\Entity\UserRole as UserRoleEntity;

class Edit extends AbstractEdit
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct('edit', []);
        $this->em = $em;
    }

    public function init()
    {
        $this->add(
            [
                'name'    => 'address',
                'type'    => Form\Element\Text::class,
                'options' => [
                    'label' => _('Adress'),

                ],
            ]
        );

        $this->add(
            [
                'name'    => 'phone',
                'type'    => Form\Element\Tel::class,
                'options' => [
                    'label' => _('Phone'),
                ],
            ]
        );

        $this->add(
            [
                'name'    => 'driver',
                'type' => DoctrineSelect::class,
                'options' => [
                    'label' => _('Driver'),
                    'object_manager' => $this->em,
                    'target_class' => UserEntity::class,
                    'property' => 'name',
                    'is_method' => true,
                    'find_method' => [
                        'name' => 'findByRoleId',
                        'params' => [
                            'roleId' => UserRoleEntity::DRIVER,
                        ]
                    ],
                ],
                'attributes' => [
                    'data-placeholder' => _('Driver'),
                ],
            ]
        );

        $this->initInputFilters();
    }

    protected function initInputFilters()
    {
        $inputFilter = new InputFilter\InputFilter();

        $this->setInputFilter($inputFilter);

        $inputFilter->add(
            [
                'name'     => 'address',
                'required' => true,
                'filters'  => [
                    ['name' => Filter\StringTrim::class],
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'     => 'phone',
                'required' => true,
                'filters'  => [
                    ['name' => Filter\StringTrim::class],
                ],
            ]
        );
    }
}
