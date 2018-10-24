<?php

namespace AppAdmin\Form;

use App\Form\AbstractForm;
use App\Form\FilterInterface;
use Zend\Form;
use App\Repository\Plugin;

abstract class AbstractFilter extends AbstractForm implements FilterInterface
{
    /**
     * @var int
     */
    protected $defaultMaxLength = 100;

    /**
     * @var string
     */
    protected $prefix = 'filter_';

    /**
     * AbstractFilter constructor.
     *
     * @param int|null|string $name
     *
     */
    public function __construct($name)
    {
        parent::__construct($name);

        $this->add([
            'name' => 'filter-on',
            'type' => Form\Element\Hidden::class,
        ]);

        $this->add([
            'name' => 'search',
            'type' => Form\Element\Button::class,
            'options' => [
                'label' => _('Search'),
            ],
            'attributes' => [
                'type' => 'submit',
            ],
        ]);

        $this->add([
            'name' => 'reset',
            'type' => Form\Element\Button::class,
            'options' => [
                'label' => _('Reset'),
            ],
            'attributes' => [
                'type' => 'reset',
            ],
        ]);
    }

    /**
     * Prepare and get params
     *
     * @return Plugin\ParameterInterface[]
     */
    public function prepareAndGetData()
    {
        $result = [];

        foreach ($this->getData() as $key => $value) {
            if (stripos($key, $this->prefix) !== 0 || $value === null) {
                continue;
            }

            $name = preg_replace("/{$this->prefix}/", "", $key, 1);
            $name = $this->hydrator->hydrateName($name);

            $result[] = new Plugin\Filter\Parameter($name, $value);
        }

        return $result;
    }

    /**
     * Filter is enabled
     *
     * @return bool
     */
    public function filterIsEnabled()
    {
        return $this->get('filter-on')->getValue() === 'true';
    }
}
