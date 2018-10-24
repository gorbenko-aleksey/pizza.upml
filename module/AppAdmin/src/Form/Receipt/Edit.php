<?php

namespace AppAdmin\Form\Receipt;

use App\Hydrator\Strategy\KiloFormatterStrategy;
use AppAdmin\Form\AbstractEdit;
use DoctrineModule\Form\Element\ObjectSelect as DoctrineSelect;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;
use Application\Entity\User as UserEntity;
use Application\Entity\Receipt as ReceiptEntity;
use Doctrine\ORM\EntityManager;
use Zend\Validator;
use App\Entity\EntityNameInterface;
use Zend\Authentication;
use DoctrineModule\Validator\NoObjectExists;
use Zend\I18n\Translator\TranslatorInterface;

class Edit extends AbstractEdit
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Authentication\AuthenticationService
     */
    private $authenticationService;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Constructor
     *
     * @param EntityManager $em
     * @param Authentication\AuthenticationService $authenticationService
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManager $em, Authentication\AuthenticationService $authenticationService, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->authenticationService = $authenticationService;
        $this->translator = $translator;

        parent::__construct('edit_receipt');
        $this->setAttribute('method', 'post');
    }

    public function init()
    {
        parent::init();

        $this->add([
            'name' => 'name',
            'type' => Form\Element\Text::class,
            'options' => [
                'label' => _('Receipt name'),

            ],
            'attributes' => [
                'placeholder' => _('Receipt name'),
                'maxlength' => 255,
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
                'maxlength' => '1000',
                'cols'      => 90,
                'rows'      => 4,
            ],
        ]);

        $this->add([
            'name'       => 'product_weight',
            'type'       => Form\Element\Number::class,
            'options'    => [
                'label' => _('Product weight, kg'),
                'value' => '',
            ],
            'attributes' => [
                'placeholder' => _('Product weight, kg'),
                'step'        => 'any',
            ],
        ]);

        $this->add([
            'type' => Form\Element\Collection::class,
            'name' => 'receipt_ingredient_weights',
            'options' => [
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => [
                    'type' => IngredientsFieldset::class,
                ],
            ],
        ]);

        $this->initInputFilters();
        $this->getHydrator()->addStrategy('productWeight', new KiloFormatterStrategy());
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
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'max' => 255,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'description',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'product_weight',
            'required' => true,
            'validators' => [
                ['name' => \Zend\I18n\Validator\IsFloat::class],
                [
                    'name' => Validator\GreaterThan::class,
                    'options' => ['min' => 0, 'inclusive' => true],
                ],
            ],
        ]);

    }

    /**
     * @param object $object
     * @param int    $flags
     *
     * @return $this|Form\Form
     */
    public function bind($object, $flags = Form\FormInterface::VALUES_NORMALIZED)
    {
        parent::bind($object, $flags);

        if (!$object->getId()) {
            $this->getInputFilter()->get('name')->getValidatorChain()->attachByName(
                NoObjectExists::class,
                [
                    'object_repository' => $this->em->getRepository(ReceiptEntity::class),
                    'fields' => 'name',
                    'messages' => [
                        'objectFound' => _('Receipt with this name already exists'),
                    ]
                ]
            );
        }

        return $this;
    }

    /**
     * @param array|\ArrayAccess|\Traversable $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->getInputFilter()->get('name')->getValidatorChain()->attach(new Validator\Regex([
            'pattern' => EntityNameInterface::PATTERN,
            'messages' => [
                Validator\Regex::NOT_MATCH => _('Illegal characters used'),
            ],
        ]));

        parent::setData($data);

        return $this;
    }

}
