<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GoogleAnalytics extends AbstractHelper
{
    /**
     * Google analytics code
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
        $this->code = $config['google_analytics']['code'];
    }

    /**
     * Render head script
     *
     * @return string
     */
    public function __invoke()
    {
        if (!$this->code) {
            return '';
        }

        return $this->getView()->partial(
            'application/helper/google-analytics',
            [
                'code' => $this->code,
            ]
        );
    }
}
