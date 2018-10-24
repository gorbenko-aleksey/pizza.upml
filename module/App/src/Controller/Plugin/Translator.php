<?php

namespace App\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\I18n\Translator as ZfTranslator;
use Zend\I18n\Translator\TranslatorInterface;

class Translator extends AbstractPlugin
{
    /**
     * Zend Framework translator
     *
     * @var ZfTranslator
     */
    protected $translator;

    /**
     * Constructor
     *
     * @param ZfTranslator $translator
     */
    public function __construct(ZfTranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Invoke
     *
     * @return TranslatorInterface
     */
    public function __invoke()
    {
        return $this->translator;
    }
}
