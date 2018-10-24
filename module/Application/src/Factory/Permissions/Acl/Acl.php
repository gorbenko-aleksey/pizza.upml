<?php

namespace Application\Factory\Permissions\Acl;

use Interop\Container\ContainerInterface;
use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Entity;
use Doctrine\ORM\EntityManager;

class Acl implements FactoryInterface
{
    /**
     * Create authentication service
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return ZendAcl
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $acl = new ZendAcl();
        $config = $container->get('Config');
        /** @var EntityManager $em */
        $em = $container->get(EntityManager::class);

        foreach ($em->getRepository(Entity\UserRole::class)->findAll() as $userRole) {
            if ($parentUserRole = $userRole->getParent()) {
                $parent = $parentUserRole->getRoleId();
            } else {
                $parent = null;
            }

            $acl->addRole($userRole->getRoleId(), $parent);
        }

        foreach ($config['acl'] as $rule) {
            foreach ($this->extractResourcesFromRule($rule) as $resource) {
                $acl->addResource($resource);
                foreach ($rule['roles'] as $role) {
                    $acl->allow($role, $resource);
                }
            }
        }

        return $acl;
    }

    /**
     * Extract resources from rule
     *
     * @param array $rule
     *
     * @return array
     */
    protected function extractResourcesFromRule(array $rule)
    {
        $resources = [];
        $rule['controller'] = (array)$rule['controller'];
        $rule['action'] = !empty($rule['action']) ? (array)$rule['action'] : [null];

        foreach ($rule['controller'] as $controller) {
            foreach ($rule['action'] as $action) {
                if (!empty($action)) {
                    $resources[] = sprintf('%s::%s', $controller, $action);
                } else {
                    $resources[] = sprintf('%s', $controller);
                }
            }
        }

        return $resources;
    }
}
