<?php

namespace AppAdmin\Form\Category;

use Zend\Form;
use Zend\Filter;
use Zend\InputFilter;
use Application\Entity\Category as CategoryEntity;
use Application\Repository\Category as CategoryRepository;
use AppAdmin\Form\AbstractEdit;
use Zend\Validator;
use App\Entity\EntityCodeInterface;
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

        $this->add(
            [
                'name'    => 'status',
                'type'    => Form\Element\Select::class,
                'options' => [
                    'label'   => _('Status'),
                    'options' => [
                        CategoryEntity::STATUS_ACTIVE     => _('Enabled'),
                        CategoryEntity::STATUS_NOT_ACTIVE => _('Disabled'),
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'name',
                'type'       => Form\Element\Text::class,
                'options'    => [
                    'label' => _('Name'),
                ],
                'attributes' => [
                    'placeholder' => _('Name'),
                    'maxlength' => 150,
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'code',
                'type'       => Form\Element\Text::class,
                'options'    => [
                    'label' => _('Code'),
                ],
                'attributes' => [
                    'placeholder' => _('Code'),
                    'maxlength'   => 150,
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'parent',
                'type'       => \DoctrineModule\Form\Element\ObjectSelect::class,
                'options'    => [
                    'label'          => _('Parent'),
                    'object_manager' => $this->em,
                    'target_class'   => CategoryEntity::class,
                    'property'       => 'parent',
                ],
                'attributes' => [
                    'options' => $this->prepareAndGetCategoriesList(),
                ],
            ]
        );

        $this->add(
            [
                'name'    => 'description_short',
                'type'    => Form\Element\Textarea::class,
                'options' => [
                    'label' => _('Brief'),
                ],
                'attributes' => [
                    'placeholder' => _('Brief'),
                    'maxlength' => 1000,
                ],
            ]
        );

        $this->add(
            [
                'name'    => 'description_full',
                'type'    => Form\Element\Textarea::class,
                'options' => [
                    'label' => _('Full'),
                ],
                'attributes' => [
                    'placeholder' => _('Full'),
                    'maxlength' => 10000,
                    'id' => 'description_full',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'html_title',
                'type'       => Form\Element\Text::class,
                'options'    => [
                    'label' => _('Html title'),
                ],
                'attributes' => [
                    'placeholder' => _('Html title'),
                    'maxlength'   => 1000,
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'meta_keywords',
                'type'       => Form\Element\Textarea::class,
                'options'    => [
                    'label' => _('Meta keywords'),
                ],
                'attributes' => [
                    'placeholder' => _('Meta keywords'),
                    'maxlength'   => 1000,
                    'cols'        => 90,
                    'rows'        => 4,
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'meta_description',
                'type'       => Form\Element\Textarea::class,
                'options'    => [
                    'label' => _('Meta description'),
                ],
                'attributes' => [
                    'placeholder' => _('Meta description'),
                    'maxlength'   => 1000,
                    'cols'        => 90,
                    'rows'        => 4,
                ],
            ]
        );

        $this->setInputFilter($this->getFilter());
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

    /**
     * Возвращает фильтры
     *
     * @return InputFilter\InputFilter
     */
    public function getFilter() : InputFilter\InputFilter
    {
        $factory = new InputFilter\Factory();
        $inputFilter = new InputFilter\InputFilter();

        $inputs = [];

        $inputs[] = $factory->createInput([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['max' => 150],
                ]
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name' => 'code',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['max' => 150],
                ],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name' => 'parent',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name' => 'description_short',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['max' => 1000],
                ]
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name' => 'description_full',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['max' => 10000],
                ]
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name' => 'html_title',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['max' => 1000],
                ]
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name' => 'meta_description',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['max' => 1000],
                ]
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name' => 'meta_keywords',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => ['max' => 1000],
                ]
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name' => 'status',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        foreach ($inputs as $input) {
            $inputFilter->add($input);
        }

        return $inputFilter;
    }

    /**
     * @return CategoryRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(CategoryEntity::class);
    }

    /**
     * Возвращает список категорий
     *
     * @return array
     */
    protected function prepareAndGetCategoriesList() : array
    {
        return $this->getRepository()->getList();
    }
}
