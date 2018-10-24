<?php

namespace Application\Controller;

use Application\Service\Seo as SeoService;
use Application\Entity\Page as PageEntity;
use Application\Repository\Page as PageRepository;

use Doctrine\ORM\EntityManager;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class PageController extends AbstractActionController
{
    /**
     * @var SeoService
     */
    private $seoService;

    /**
     * @var PageRepository
     */
    private $pageRepository;

    /**
     * @param SeoService $seoService
     * @param EntityManager $em
     */
    public function __construct(SeoService $seoService, EntityManager $em)
    {
        $this->pageRepository = $em->getRepository(PageEntity::class);
        $this->seoService = $seoService;
    }

    public function indexAction()
    {
        if (!($page = $this->pageRepository->findOneByCode($this->params('code')))) {
            return $this->notFoundAction();
        }

        if ($page->getStatus() !== PageEntity::STATUS_ACTIVE) {
            return $this->notFoundAction();
        }

        $this->seoService->addSeoSuffix($page);

        if ($page->isDriverEnable()) {
            $driver = $this->getDriverByPage($page);

            if (!$this->getRequest()->isXmlHttpRequest()) {
                return $driver->action($page);
            }

            if ($this->getRequest()->isXmlHttpRequest() && $driver->isAjaxAccepted()) {
                return $driver->ajaxAction($page);
            }
        }

        return new ViewModel(['page' => $page]);
    }

    /**
     * @param PageEntity $page
     *
     * @return object
     */
    protected function getDriverByPage(PageEntity $page)
    {
        return $this->{'pageDriver' . ucwords($page->getDriver())}();
    }
}
