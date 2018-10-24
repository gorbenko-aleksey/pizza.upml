<?php

namespace Application\View\Helper;

use Application\Service\Seo as SeoService;
use Zend\View\Helper\AbstractHelper;

class SeoData extends AbstractHelper
{
    /**
     * @var SeoService
     */
    private $seoService;

    /**
     * Constructor
     *
     * @param SeoService $seoService
     */
    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Applies seo rules
     *
     * @return string
     */
    public function __invoke()
    {
        $view = $this->getView();
        $seoData = $this->seoService->getSeoData($view->serverUrl(true));

        foreach ($seoData as $seo) {
            if ($seo->getName() === 'title') {
                $view->headTitle($seo->getResult());
                continue;
            }

            $view->headMeta()->setName($seo->getName(), $seo->getResult());
        }
    }
}
