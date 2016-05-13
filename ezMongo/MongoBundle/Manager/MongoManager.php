<?php
namespace ezMongo\MongoBundle\Manager;

use ezMongo\MongoBundle\Connection\ConnectionFactory;
use ezMongo\MongoBundle\Repository\RepositoryInterface;
use MongoDB\Collection;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Class MongoManager
 *
 * @package Promo\BaseBundle\Manager
 */
class MongoManager implements ManagerInterface
{
    /**
     * @var null|ConnectionFactory
     */
    private $connectionFactory;

    /**
     * @var array
     */
    private $bundleAliases = array();

    /**
     * @var array   Should contain an Array of MongoConnection
     */
    private $connections = array();

    /**
     * @var array
     */
    private $collectionDetails = array();

    /**
     * @var array
     */
    private $collections = array();

    /**
     * @var array
     */
    private $repositories = array();

    /**
     * Inject a factory that can build a MongoDB Connection based on the parameters
     * @param ConnectionFactory $factory
     */
    public function setConnectionFactory(ConnectionFactory $factory)
    {
        $this->connectionFactory = $factory;
    }

     /**
     * Inject the bundle alias names in order to use for reproducing the "DoctrineManager" repository getter
     * @param array $aliases
     */
    public function setBundleAliases(array $aliases)
    {
        $this->bundleAliases = $aliases;
    }
    /**
     * Inject configuration data - We assume that the given data is correct
     * @param array $data
     */
    public function setConfig(array $data)
    {
        $this->buildConnections($data['connections']);
        $this->buildCollectionAliases($data['collections']);
        $this->connectionFactory = null;
    }

    /**
     * Inject configuration data and convert to connection strings - We assume that the given data is correct
     * @param array $data
     */
    public function buildConnections(array $data)
    {
        foreach ($data as $connetionAlias => $connectionDetails) {
            $this->connections[$connetionAlias] = $this->connectionFactory->createConnection(
                explode(',', $connectionDetails['hostname']),
                explode(',', $connectionDetails['port']),
                explode(',', $connectionDetails['username']),
                explode(',', $connectionDetails['password'])
            );
        }
    }

    /**
     * Create collection to Connection reference
     * @param array $data
     */
    public function buildCollectionAliases(array $data)
    {
        foreach ($data as $collectionAlias => $collectionInfo) {
            if (!isset($this->connections[$collectionInfo['connection']])) {
                throw new InvalidConfigurationException(
                    sprintf(
                        'Collection <%s> needs <%s> connection which is not defined in config.yml',
                        $collectionAlias,
                        $collectionInfo['connection']
                    )
                );
            }
            $this->collectionDetails[$collectionAlias] = array(
                'collectionDatabase' => $collectionInfo['database'],
                'collectionName' => $collectionInfo['collection'],
                'collectionConnection' => $this->connections[$collectionInfo['connection']]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection(string $alias): Collection
    {
        if (isset($this->collections[$alias]) === false) {
            $collectionManager = $this->collectionDetails[$alias]['collectionConnection'];
            $this->collections[$alias] = new Collection(
                $collectionManager->getMongoManager(),
                $this->collectionDetails[$alias]['collectionDatabase'],
                $this->collectionDetails[$alias]['collectionName']
            );
        }
        return $this->collections[$alias];
    }

    /**
     * Retrieve the Repository
     * @param  string $alias
     * @return RepositoryInterface
     */
    public function getRepository(string $alias): RepositoryInterface
    {
        if (!isset($this->repositories[$alias])) {
            $this->buildRepository($alias);
        }
        return $this->repositories[$alias];
    }

    /**
     * Build the Repository class from Alias Name
     * @param string $alias
     */
    public function buildRepository(string $alias)
    {
        $repositoryClass = $this->createRepository($alias);
        $collection = $this->getCollection($repositoryClass->getRepositoryAlias());
        $repositoryClass->addCollectionManager($collection);
        $this->repositories[$alias] = $repositoryClass;
    }

    /**
     * Builds and instance the Repoitory
     * @param string $alias
     * @return RepositoryInterface
     */
    protected function createRepository(string $alias): RepositoryInterface
    {
        list($bundleAlias, $repositoryName) = explode(':', $alias);
        try {
            $namespace = explode('\\', $this->bundleAliases[$bundleAlias]);
            array_pop($namespace);
            $namespace = implode('\\', $namespace);
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Unknown or invalid namespace <%s>. Loaded namespace aliases: %s",
                    $bundleAlias,
                    implode(', ', array_keys($this->bundleAliases))
                )
            );
        }
        $classFullName = '\\' . $namespace . '\\Repository\\' . $repositoryName;
        if (substr($classFullName, -10) !== 'Repository') {
            $classFullName .= 'Repository';
        }
        try {
            $repositoryClass = new $classFullName;
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Class <%s> was not found or could not be instantiated! <%s>",
                    $classFullName,
                    $e->getMessage()
                )
            );
        }
        if ($repositoryClass instanceof RepositoryInterface === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Class <%s> does not implement <%s>!",
                    $classFullName,
                    RepositoryInterface::class
                )
            );
        }
        return $repositoryClass;
    }
}
