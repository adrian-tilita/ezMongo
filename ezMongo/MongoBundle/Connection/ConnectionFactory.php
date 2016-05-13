<?php
namespace ezMongo\MongoBundle\Connection;

/**
 * Class ConnectionFactory
 *
 * @package ezMongo\MongoBundle\Connection
 */
class ConnectionFactory
{
    /**
     * Create a MongoDB Connection
     * @param array $hostname
     * @param array $port
     * @param array $username
     * @param array $password
     * @param array $connectionOptions
     * @return MongoConnection
     */
    public function createConnection(
        array $hostname,
        array $port = array(),
        array $username = array(),
        array $password = array(),
        array $connectionOptions = array()
    ) {
        $connectionString = $this->createConnectionString($hostname, $port, $username, $password, $connectionOptions);
        return new MongoConnection($connectionString);

    }

    /**
     * Convert a set off parameters to a MongoDB Connection String
     * @param array $hostname
     * @param array $port
     * @param array $username
     * @param array $password
     * @param array $connectionOptions
     * @return string ex: mongodb://[user]:[password]@[hostname]:[port],[hostname]:[port]/[?connectionOptions]
     */
    private function createConnectionString($hostname, $port, $username, $password, $connectionOptions): string
    {
        $connectionString = 'mongodb://';
        $connectionParts = array();
        foreach ($hostname as $groupLine => $hostnameValue) {
            $tempBuild = '';
            if (isset($username[$groupLine]) &&
                $username[$groupLine] != '' &&
                isset($password[$groupLine]) &&
                $password[$groupLine] != '') {
                $tempBuild .= trim($username[$groupLine]) . ':' . trim($password[$groupLine]) . '@';
            }
            $tempBuild .= trim($hostnameValue);
            if (isset($port[$groupLine])) {
                $tempBuild .= ':' . trim($port[$groupLine]);
            }
            $connectionParts[] = $tempBuild;
        }
        $connectionString .= implode($connectionParts, ',') . '/';
        return $connectionString;
    }
}
