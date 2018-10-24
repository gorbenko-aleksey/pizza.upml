<?php

namespace AppAdmin\Form;

use App\Form\AbstractForm;
use App\Form\FilterInterface;
use Zend\Filter as ZendFilter;
use Zend\InputFilter as ZendInputFilter;
use App\Repository\Plugin;

class Sorter extends AbstractForm implements FilterInterface
{
    /**
     * @var string
     */
    protected $prefix = 'sort_';

    /**
     * AbstractSorter constructor.
     */
    public function __construct()
    {
        parent::__construct('sorter');

        $this->add([
            'name' => "{$this->prefix}field",
        ]);

        $this->add([
            'name' => "{$this->prefix}type",
        ]);

        $this->initInputFilters();
    }

    /**
     * Prepare and get params
     *
     * @return Plugin\ParameterInterface[]
     */
    public function prepareAndGetData()
    {
        $data = $this->getData();

        if (empty($data["{$this->prefix}field"]) || empty($data["{$this->prefix}type"])) {
            return [];
        }

        $name = $data["{$this->prefix}field"];
        $name = $this->hydrator->hydrateName($name);

        return [new Plugin\Sorter\Parameter($name, $data["{$this->prefix}type"])];
    }

    /**
     * Init validators and filters
     */
    protected function initInputFilters()
    {
        $inputFilter = new ZendInputFilter\InputFilter();

        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => "{$this->prefix}field",
            'filters' => [
                ['name' => ZendFilter\StringTrim::class],
            ],
        ]);

        $inputFilter->add([
            'name' => "{$this->prefix}type",
            'filters' => [
                ['name' => ZendFilter\StringTrim::class],
            ],
        ]);
    }
}
