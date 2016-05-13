<?php
namespace ezMongo\MongoBundle\Repository;

use ezMongo\MongoBundle\Adapter\MongoDocumentAdapter;

/**
 * Interface RepositoryInterface
 *
 * @package ezMongo\MongoBundle\Repository
 */
interface RepositoryInterface
{
    /**
     * @return string   Should return the alias off the repository defined in config.yml
     */
    public function getRepositoryAlias(): string;

    /**
     * Inject an Adapter for the common results
     *
     * @param $adapter
     */
    public function useAdapter(MongoDocumentAdapter $adapter);

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier - It will be automatically converted to ObjectID
     * @return object The object.
     */
    public function find($id);

    /**
     * Finds all objects in the repository.
     * @return array The objects.
     */
    public function findAll();

    /**
     * TO BE DOCUMENTED
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria The criteria.
     * @return object The object.
     */
    public function findOneBy(array $criteria);
}
