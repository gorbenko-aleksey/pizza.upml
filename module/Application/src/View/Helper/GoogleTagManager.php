<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GoogleTagManager extends AbstractHelper
{
    /**
     * Google tag manager code
     *
     * @var string
     */
    protected $code;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->code = $config['google_tag_manager']['code'];
    }

    /**
     * Render head script
     *
     * @return string
     */
    public function renderHeadScript()
    {
        return $this->render();
    }

    /**
     * Render body script
     *
     * @return string
     */
    public function renderBodyScript()
    {
        return $this->render(false);
    }

    /**
     * Render script
     *
     * @param bool $isHead
     *
     * @return string
     */
    private function render($isHead = true)
    {
        if (!$this->code) {
            return '';
        }

        return $this->getView()->partial(
            'application/helper/google-tag-manager',
            [
                'code' => $this->code,
                'isHead' => $isHead,
            ]
        );
    }
}
