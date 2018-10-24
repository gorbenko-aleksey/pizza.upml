<?php

namespace AppAdmin\Form\SiteMap;

use AppAdmin\Form\AbstractEdit;
use Zend\Filter;
use Zend\Form;
use Zend\InputFilter;
use Zend\Validator;

class Edit extends AbstractEdit
{
    public function init()
    {
        parent::init();

        $this->add([
            'name' => 'body',
            'type' => Form\Element\Textarea::class,
            'options' => [
                'label' => _('Sitemap.xml'),
            ],
            'attributes' => [
                'id' => 'body',
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
            'name' => 'body',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
        ]);

        // @todo Создать XML-валидатор и использовать его
        $xmlValidator = new Validator\Callback();
        $xmlValidator->setCallback(function ($value) use ($xmlValidator) {
            libxml_use_internal_errors(true);

            simplexml_load_string($value);
            $errors = libxml_get_errors();

            libxml_clear_errors();

            if (empty($errors)) {
                return true;
            }

            $xmlValidator->setMessage($errors[0]->message);

            return false;
        });

        $inputFilter->get('body')->getValidatorChain()->attach($xmlValidator);
    }

}
