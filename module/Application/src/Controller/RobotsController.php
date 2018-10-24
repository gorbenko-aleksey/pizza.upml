<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Application\Entity;
use Application\Repository;

class RobotsController extends AbstractActionController
{
    /**
     * @var Repository\Robots
     */
    protected $repository;

    /**
     * RobotsController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(Entity\Robots::class);
    }

    public function indexAction()
    {
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/plain');

        $robots = $this->repository->findOneBy([]);

        $viewModel = new ViewModel(['robots' => $robots]);
        $viewModel->setTerminal(true);

        return $viewModel;
    }
}
