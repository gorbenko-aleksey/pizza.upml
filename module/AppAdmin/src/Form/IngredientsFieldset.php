<?php

namespace AppAdmin\Form;

use App\Hydrator\Strategy\KiloFormatterStrategy;
use Zend\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Application\Entity\Ingredient;
use Zend\Filter;
use DoctrineModule\Form\Element\ObjectSelect as DoctrineSelect;
use Doctrine\ORM\EntityManager;
use Zend\I18n\Translator\TranslatorInterface;

class IngredientsFieldset extends Fieldset implements InputFilterProviderInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * IngredientsFieldset constructor.
     *
     * @param EntityManager $em
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManager $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;

        parent::__construct('Ingredient_fs');
    }

    public function init()
    {
        $this->add([
            'name'       => 'ingredient',
            'type'       => DoctrineSelect::class,
            'options'    => [
                'label'          => _('Ingredient'),
                'object_manager' => $this->em,
                'target_class'   => Ingredient::class,
                'property'       => 'name',
                'is_method'      => true,
                'display_empty_item' => true,
                'empty_item_label' => _('All'),
                'find_method'    => [
                    'name'   => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy'  => ['id' => 'ASC'],
                    ],
                ],
            ],
            'attributes' => [
                'data-placeholder' => $this->translator->translate('Ingredient'),
            ],
        ]);

        $this->add([
            'name'       => 'weight',
            'type'       => Form\Element\Number::class,
            'options'    => [
                'label' => _('Weight, kg'),
            ],
            'attributes' => [
                'placeholder' => _('Weight, kg'),
                'step'        => 'any',
            ],
        ]);

        $this->getHydrator()->addStrategy('weight', new KiloFormatterStrategy());
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'ingredient' => [
                'required' => true,
                'filters'  => [
                    ['name' => Filter\StringTrim::class],
                ],
            ],
            'weight'     => [
                'required'   => true,
                'validators' => [
                    ['name' => \Zend\I18n\Validator\IsFloat::class],
                    [
                        'name'    => \Zend\Validator\GreaterThan::class,
                        'options' => ['min' => 0],
                    ],
                ],
            ],
        ];
    }
}
