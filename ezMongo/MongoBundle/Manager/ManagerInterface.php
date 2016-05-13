<?php
namespace ezMongo\MongoBundle\Manager;

use MongoDB\Collection;
use ezMongo\MongoBundle\Repository\RepositoryInterface;

/**
 * Interface ManagerInterface
 *
 * @package ezMongo\MongoBundle\Manager
 */
interface ManagerInterface
{
    /**
     * Retrieves a Collection instance
     * @param string $alias
     * @return Collection
     */
    public function getCollection(string $alias): Collection;

    /**
     * Retrieves a repository
     * @param string $alias     The same alias form as Doctrine's "BundleAlias:Entity"
     * @return RepositoryInterface
     */
    public function getRepository(string $alias): RepositoryInterface;
}
