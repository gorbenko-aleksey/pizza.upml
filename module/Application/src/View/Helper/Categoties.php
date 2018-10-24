<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Category as CategoryEntity;
use Zend\Navigation\Navigation;
use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;

class Categoties extends AbstractHelper
{
     /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager, EntityManager $em)
    {
        $this->serviceManager = $serviceManager;
        $this->em = $em;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $pages = [];
        $router = $this->serviceManager->get('router');
        $categories = $this->em->getRepository(CategoryEntity::class)->findBy(
            ['level' => 1, 'status' => CategoryEntity::STATUS_ACTIVE]
        );
        /** @var CategoryEntity $category */
        foreach ($categories as $category) {
            $pages[] = [
                'label' => $category->getName(),
                'route' => 'category',
                'params' => [
                    'code' => $category->getCode(),
                ],
                'router' => $router,
            ];
        }

        $container = new Navigation($pages);

        return $this->getView()->navigation()->menu($container)->setPartial('application/helper/categoties')->render();
    }
}
