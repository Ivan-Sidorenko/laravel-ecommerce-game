<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Database helpers. Provides parameters for connecting to the MySQL server.
 *
 * @category  Demo
 * @package   SagepayDemo
 * @copyright (c) 2013, Sagepay Europe Ltd.
 */
class HelperDatabase
{

    /**
     * Singleton instance for Helper database
     *
     * @var HelperDatabase
     */
    private static $_instance = null;

    /**
     * PDO Instance
     *
     * @var PDO
     */
    private $_pdo = null;

    /**
     * Connection hostname
     *
     * @var string
     */
    private $_hostname;

    /**
     * Connection username
     *
     * @var string
     */
    private $_username;

    /**
     * Connection user's password
     *
     * @var string
     */
    private $_password;

    /**
     * Connection database name
     *
     * @var string
     */
    private $_database;

    /**
     * Array of PDOStatement::errorInfo()
     *
     * @see PDOStatement::errorInfo()     *
     * @var string
     */
    private $_error;

    /**
     * Constructor for HelperDatabase
     *
     * @throws PDOException
     */
    private function __construct()
    {
        $config = $this->_readConfig();
        foreach ($config as $property => $value)
        {
            $privateProperty = '_' . $property;
            if (property_exists($this, $privateProperty))
            {
                $this->$privateProperty = $value;
            }
        }

        $dns = 'mysql:host=' . $this->_hostname . ';dbname=' . $this->_database;
        $this->_pdo = new PDO($dns, $this->_username, $this->_password);
    }

    /**
     * Read configuration file
     *
     * @return array
     */
    private function _readConfig()
    {
        return include DEMO_PATH . '/config.php';
    }

    /**
     * Get PDO
     *
     * @return PDO|null
     */
    public function getPdo()
    {
        return $this->_pdo;
    }

    /**
     * Get database name
     *
     * @return string
     */
    public function getDatabase()
    {
        return $this->_database;
    }

    /**
     * Get user name
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * Run the SQL query, prepare and execute
     *
     * @param string $query
     * @param array $params
     *
     * @return PDOStatement|null
     */
    public function execute($query, $params = array())
    {
        $statement = $this->_pdo->prepare($query);
        if ($statement && $statement->execute($params))
        {
            return $statement;
        }
        $this->_error = $statement->errorInfo();
        return null;
    }

    /**
     * Execute multiple SQL queries
     *
     * @param array $queries
     */
    public function executeMultiple(array $queries)
    {
        foreach ($queries as $query)
        {
            if (is_array($query))
            {
                $this->execute($query['sql'], $query['param']);
            }
            else
            {
                $this->execute($query);
            }
        }
    }

    /**
     * Get MySQL error
     *
     * @return array
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * Close connection
     */
    public function close()
    {
        $this->_pdo = null;
    }

    /**
     * Get instance
     *
     * @return HelperDatabase
     */
    public static function getInstance()
    {
        if (self::$_instance === null)
        {
            self::$_instance = new HelperDatabase();
        }
        return self::$_instance;
    }


}