<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Application\Entity;
use Application\Repository;

class SiteMapController extends AbstractActionController
{
    /**
     * @var Repository\SiteMap
     */
    protected $repository;

    /**
     * RobotsController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(Entity\SiteMap::class);
    }

    public function indexAction()
    {
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/xml');

        $siteMap = $this->repository->findOneBy([]);

        $viewModel = new ViewModel(['siteMap' => $siteMap]);
        $viewModel->setTerminal(true);

        return $viewModel;
    }
}
