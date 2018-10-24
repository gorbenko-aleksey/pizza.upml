<?php

namespace AppAdmin\Form\Ingredient;

use AppAdmin\Form\AbstractEdit;
use Zend\Form;
use Zend\InputFilter;
use Doctrine\ORM\EntityManager;
use Application\Entity\Ingredient as IngredientEntity;

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
                [
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => [
                        'object_repository' => $this->em->getRepository(IngredientEntity::class),
                        'fields' => 'name',
                        'messages' => [
                            'objectFound' => _('A ingredient with this name already exists'),
                        ],
                    ],
                ],
            ],
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
            $this->object->getName() === trim($data['name'])
        ) {
            //remove from validation field name
            unset($group[array_search('name', $group)]);
        }

        $this->setValidationGroup($group);

        return parent::setData($data);
    }
}
