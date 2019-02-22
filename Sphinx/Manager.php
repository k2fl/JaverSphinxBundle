<?php

namespace Javer\SphinxBundle\Sphinx;

use Javer\SphinxBundle\Logger\SphinxLogger;

/**
 * Class Manager
 *
 * @package Javer\SphinxBundle\Sphinx
 */
class Manager
{
    /**
     * @var SphinxLogger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var PDO
     */
    protected $connection;

    /**
     * Connection constructor.
     *
     * @param SphinxLogger $logger
     * @param string       $host
     * @param string       $port
     */
    public function __construct(SphinxLogger $logger, string $host, string $port)
    {
        $this->logger = $logger;
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * Returns an established connection to Sphinx server.
     *
     * @return mysqli
     */
    protected function getConnection(): \mysqli
    {
        if (null === $this->connection) {
            $this->connection = new \mysqli($this->host, null, null, null, $this->port);
            if ($this->connection->connect_error) {
                throw new \RuntimeException(sprintf('SphinxQL connection error: %s.', $this->connection->connect_error));
            }
        }

        return $this->connection;
    }

    /**
     * Creates a new query.
     *
     * @return Query
     */
    public function createQuery(): Query
    {
        return new Query($this->getConnection(), $this->logger);
    }

    /**
     * Closes the current connection.
     */
    public function closeConnection()
    {
        if (null !== $this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }
}
