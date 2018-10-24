<?php

namespace App\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use App\Service\Sentry as SentryService;

class Sentry extends AbstractPlugin
{
    /**
     * Sentry service
     *
     * @var SentryService
     */
    protected $sentryService;

    /**
     * Constructor
     *
     * @param SentryService $sentryService
     */
    public function __construct(SentryService $sentryService)
    {
        $this->sentryService = $sentryService;
    }

    /**
     * Invoke
     *
     * @return SentryService
     */
    public function __invoke()
    {
        return $this->sentryService;
    }
}
