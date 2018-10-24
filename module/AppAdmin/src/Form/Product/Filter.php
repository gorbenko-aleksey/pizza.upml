<?php

namespace AppAdmin\Form\Product;

use Zend\Form;
use AppAdmin\Form\AbstractFilter;
use Application\Entity\Product as ProductEntity;
use Application\Entity\Category as CategoryEntity;
use Application\Repository\Category as CategoryRepository;
use Doctrine\ORM\EntityManager;

class Filter extends AbstractFilter
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
        parent::__construct('filter');
        $this->em = $em;
    }

    public function init()
    {
        parent::init();

        $this->add([
            'name'       => $this->prefix . 'status',
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
            'name'       => $this->prefix . 'name',
            'type'       => Form\Element\Text::class,
            'options'    => [
                'label' => _('Name'),
            ],
            'attributes' => [
                'placeholder' => _('Name'),
                'maxlength'   => $this->defaultMaxLength,
            ],
        ]);

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

        $this->add([
            'name'       => $this->prefix . 'category',
            'type'       => Form\Element\Select::class,
            'options'    => [
                'label' => _('Category'),
            ],
            'attributes' => [
                'placeholder' => _('Category'),
                'options'     => $this->prepareAndGetCategories(),
            ],
        ]);
    }

    /**
     * Возвращает статусы в виде Ид => Имя
     *
     * @return array
     */
    protected function prepareAndGetStatuses(): array
    {
        return [
            ''                       => 'All',
            ProductEntity::STATUS_ENABLED  => 'Enabled',
            ProductEntity::STATUS_DISABLED => 'Disabled',
        ];
    }

    /**
     * Возвращает категории в виде Ид => Имя
     *
     * @return array
     */
    protected function prepareAndGetCategories(): array
    {
        $list = ['' => 'Select category'];

        foreach ($this->getRepository()->getList() as $key => $value) {
            $list[$key] = $value;
        }

        unset($list[1]);

        return $list;
    }

    /**
     * @return CategoryRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(CategoryEntity::class);
    }

}
