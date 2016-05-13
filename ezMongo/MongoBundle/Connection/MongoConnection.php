<?php
namespace ezMongo\MongoBundle\Connection;

use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;

/**
 * Class MongoConnection
 *
 * @package ezMongo\MongoBundle\Connection
 */
class MongoConnection
{
    /**
     * @var string  A mongodb:// connection URI
     */
    private $connectionString;

    /**
     * @var null|Manager;
     */
    private $mongoManagerInstance = null;

    /**
     * MongoConnection constructor.
     *
     * @param string $connectionString  A mongodb:// connection URI
     * @link https://docs.mongodb.com/manual/reference/connection-string/
     */
    public function __construct(string $connectionString)
    {
        $this->connectionString = $connectionString;
    }

    /**
     * Connect to a MongoDB Server
     * @throws MongoDB\Driver\Exception\ConnectionTimeoutException
     * @throws MongoDB\Driver\Exception\InvalidArgumentException
     * @throws MongoDB\Driver\Exception\RuntimeException
     */
    public function connect()
    {
        if ($this->isConnected() === true) {
            return;
        }
        // MongoDB connection is "lazy" so we force a connection.
        $command = new Command(['ping' => 1]);
        $this->getMongoManager()->executeCommand('System', $command);
    }

    /**
     * Verify if a connection to MongoDB has been established
     * @return bool
     */
    public function isConnected(): bool
    {
        return !empty($this->getMongoManager()->getServers());
    }

    /**
     * Retrieve the MongoManager instance
     * @return MongoManager
     */
    public function getMongoManager(): Manager
    {
        if (is_null($this->mongoManagerInstance) === true) {
            $this->mongoManagerInstance = new Manager($this->connectionString);
        }
        return $this->mongoManagerInstance;
    }
}
