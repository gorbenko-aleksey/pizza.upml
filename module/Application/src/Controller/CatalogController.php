<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Application\Entity\Category as CategoryEntity;
use Application\Repository\Category as CategoryRepository;

class CatalogController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function indexAction()
    {
        $code = $this->params('code');

        if ($code) {
            $category = $this->getRepository()->findOneBy(
                ['code' => $code, 'status' => CategoryEntity::STATUS_ACTIVE]
            );
            if (!$category) {
                return $this->notFoundAction();
            }
        } else {
            $categories = $this->getRepository()->findBy(
                ['level' => 1, 'status' => CategoryEntity::STATUS_ACTIVE]
            );
        }

        return new ViewModel(['categories' => 
            isset($categories) ? $categories : [$category],
        ]);
    }

    /**
     * @return CategoryRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(CategoryEntity::class);
    }
}
