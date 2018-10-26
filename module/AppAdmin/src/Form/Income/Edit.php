<?php

namespace AppAdmin\Form\Income;

use AppAdmin\Form\AbstractEdit;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;
use Doctrine\ORM\EntityManager;
use Zend\Authentication;
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

        parent::__construct('edit_income');
        $this->setAttribute('method', 'post');
    }

    public function init()
    {
        parent::init();

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
            'type' => Form\Element\Collection::class,
            'name' => 'ingredients',
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
    }

    /**
     * Init validators and filters
     */
    protected function initInputFilters()
    {
        $inputFilter = new InputFilter\InputFilter();

        $inputFilter->add([
            'name' => 'description',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);
    }
}
