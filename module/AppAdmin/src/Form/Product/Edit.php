<?php

namespace AppAdmin\Form\Product;

use AppAdmin\Form\AbstractEdit;
use Application\Entity\Product;
use Application\Entity\ProductImage;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;
use Zend\Validator;
use Zend\ServiceManager\ServiceManager;
use App\Entity\EntityCodeInterface;

class Edit extends AbstractEdit
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * Edit constructor.
     *
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        parent::__construct('edit');

        $this->setAttribute('method', 'post');

        $this->add([
            'name'       => 'status',
            'type'       => Form\Element\Select::class,
            'options'    => [
                'label' => _('Status'),
            ],
            'attributes' => [
                'placeholder' => _('Status'),
                'options'     => $this->prepareAndGetStatuses(),
            ],
        ]);

        $this->add([
            'name'       => 'name',
            'type'       => Form\Element\Text::class,
            'options'    => [
                'label' => _('Name'),
            ],
            'attributes' => [
                'placeholder' => _('Name'),

            ],
        ]);

        $this->add([
            'name'       => 'code',
            'type'       => Form\Element\Text::class,
            'options'    => [
                'label' => _('Code'),
            ],
            'attributes' => [
                'placeholder' => _('Code'),

            ],
        ]);

        $this->add([
            'name'       => 'short_description',
            'type'       => Form\Element\Textarea::class,
            'options'    => [
                'label' => _('Brief'),
            ],
            'attributes' => [
                'maxlength' => '1000',
                'cols'      => 90,
                'rows'      => 4,
            ],
        ]);

        $this->add([
            'name'       => 'full_description',
            'type'       => Form\Element\Textarea::class,
            'options'    => [
                'label' => _('Full'),
            ],
            'attributes' => [
                'maxlength' => '20000',
                'cols'      => 90,
                'rows'      => 8,
            ],
        ]);

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
                    'maxlength'   => '1000',
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
                    'maxlength'   => '1000',
                    'cols'        => 90,
                    'rows'        => 4,
                ],
            ]
        );

        $this->add([
            'name'       => 'category',
            'type'       => \DoctrineModule\Form\Element\ObjectSelect::class,
            'options'    => [
                'label'          => _('Category'),
                'object_manager' => $this->serviceManager->get('doctrine.entitymanager.orm_default'),
                'target_class'   => '\Application\Entity\Category',
                'property'       => 'name',
                'is_method'      => true,
                'display_empty_item' => true,
                'empty_item_label'   => '---',
            ],
        ]);

        $this->add([
            'name'       => 'receipt',
            'type'       => \DoctrineModule\Form\Element\ObjectSelect::class,
            'options'    => [
                'label'          => _('Receipt'),
                'object_manager' => $this->serviceManager->get('doctrine.entitymanager.orm_default'),
                'target_class'   => '\Application\Entity\Receipt',
                'property'       => 'name',
                'is_method'      => true,
                'display_empty_item' => true,
                'empty_item_label'   => '---',
            ],
        ]);
        
        
        $this->add([
            'name'       => 'price',
            'type'       => Form\Element\Number::class,
            'options'    => [
                'label' => 'Price',
            ],
            'attributes' => [
                'required' => 'required',
                'step' => '0.01',
            ],
        ]);

        $this->add([
            'name' => 'image_upload',
            'type' => FileFieldset::class,
        ]);

        $this->setInputFilter($this->getFilter());
    }

    /**
     * @return InputFilter\InputFilter
     */
    public function getFilter(): InputFilter\InputFilter
    {
        $factory = new InputFilter\Factory();
        $inputFilter = new InputFilter\InputFilter();

        $inputs = [];

        $inputs[] = $factory->createInput([
            'name'     => 'state',
            'required' => true,
            'filters'  => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name'     => 'name',
            'required' => true,
            'filters'  => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name'     => 'code',
            'required' => false,
            'filters'  => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name'     => 'consist',
            'required' => false,
            'filters'  => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name'     => 'short_description',
            'required' => false,
            'filters'  => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name'     => 'full_description',
            'required' => false,
            'filters'  => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name'     => 'html_title',
            'required' => false,
            'filters'  => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name'     => 'meta_keywords',
            'required' => false,
            'filters'  => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name'     => 'meta_description',
            'required' => false,
            'filters'  => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        $inputs[] = $factory->createInput([
            'name'     => 'price',
                'required' => true,
                'validators' => [
                    [
                        'name' => \Zend\Validator\GreaterThan::class,
                        'options' => [
                            'min' => 0,
                            'inclusive' => true,
                        ],
                    ]
                ],
            ]);

        foreach ($inputs as $input) {
            $inputFilter->add($input);
        }

        return $inputFilter;
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

    public function prepareFiles()
    {
        $file = $this->get('image_upload')->get('file')->getValue();
        $isRemoving = $this->get('image_upload')->get('remove')->getValue();

        /** @var Product $product */
        $product = $this->object;

        if ($isRemoving) {
            $product->setImage(null);
        }

        if (!empty($file['name'])) {
            $image = new ProductImage();
            $product->setImage($image);

            $this->prepareFile($image, $file);
        }
    }

    /**
     * @return array
     */
    protected function prepareAndGetStatuses(): array
    {
        return [
            Product::STATUS_ENABLED     => 'Enabled',
            Product::STATUS_DISABLED    => 'Disabled',
        ];
    }

}
