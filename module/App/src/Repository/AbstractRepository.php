<?php

namespace App\Repository;

use Doctrine\ORM;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\Paginator\Paginator;
use Zend\Stdlib\InitializableInterface;
use Doctrine\Common\Collections;
use App\Entity;

abstract class AbstractRepository extends ORM\EntityRepository implements RepositoryInterface
{
    /**
     * @var PluginManager
     */
    protected $pluginManager;

    /**
     * @var string
     */
    protected $collectionClass = Collections\ArrayCollection::class;

    /**
     * AbstractRepository constructor.
     *
     * @param ORM\EntityManager $em
     * @param ORM\Mapping\ClassMetadata $class
     */
    public function __construct(ORM\EntityManager $em, ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->pluginManager = new PluginManager($class);

        if (in_array(InitializableInterface::class, class_implements($this))) {
            $this->init();
        }
    }

    /**
     * Fetch all rows as page
     *
     * @param int $rowsPerPage
     * @param int $page
     * @param Plugin\ParameterInterface[] $parameters
     *
     * @return Paginator
     */
    public function paginatorFetchAll($rowsPerPage, $page, array $parameters = [])
    {
        $query = $this->createQueryBuilder($this->getEntityName());

        $this->pluginManager->apply($query, $parameters);

        return $this->paginator($query, $rowsPerPage, $page);
    }

    /**
     * Save entity
     *
     * @param Entity\EntityInterface $entity
     */
    public function save(Entity\EntityInterface $entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    /**
     * Remove entity
     *
     * @param Entity\EntityInterface $entity
     */
    public function remove(Entity\EntityInterface $entity)
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    /**
     * Make paginator for query
     *
     * @param ORM\QueryBuilder $query
     * @param int $rowsPerPage
     * @param int $page
     *
     * @return Paginator
     */
    protected function paginator(ORM\QueryBuilder $query, $rowsPerPage, $page)
    {
        $ormPaginator = new ORM\Tools\Pagination\Paginator($query);
        $doctrinePaginatorAdapter = new DoctrinePaginator($ormPaginator);

        $paginator = new Paginator($doctrinePaginatorAdapter);
        $paginator->setDefaultItemCountPerPage($rowsPerPage);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }

    /**
     * Create collection from array of objects
     *
     * @param array $data
     *
     * @return Collections\Collection
     */
    protected function createCollection(array $data)
    {
        $class = $this->collectionClass;

        return new $class($data);
    }
}
