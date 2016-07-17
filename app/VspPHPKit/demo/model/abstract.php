<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Interface for integration models
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
abstract class ModelAbstract
{

    /**
     * Database table name
     *
     * @var string
     */
    protected $table;

    /**
     * List of database table fields
     *
     * @var array
     */
    protected $tableFieldsMap = array();

    /**
     * Database helper
     *
     * @var HelperDatabase
     */
    protected $dbHelper;

    /**
     * Array of errors PDO execution
     *
     * @var array
     */
    protected $error;

    /**
     * Constructor for ModelAbstract
     */
    public function __construct()
    {
        $this->dbHelper = HelperDatabase::getInstance();
    }

    /**
     * Returns new instance of model
     *
     * @param string $model     Model name
     *
     * @return ModelAbstract
     */
    static public function factory($model)
    {
        $modelClass = 'Model' . $model;
        return new $modelClass();
    }

    /**
     * Prepares and executes an INSERT statement to add new records to a database table
     *
     * @param array $data   Associated array of fileds and values
     *
     * @return PDOStatement|null
     */
    public function insert($data)
    {
        $defaultValues = array(
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        );
        $data = array_merge($defaultValues, $data);
        $order = $this->filter($data);
        $fields = implode(', ', array_keys($order));
        $values = implode(', ', array_fill(0, count($order), '?'));
        $query = 'INSERT INTO `' . $this->table . '` (' . $fields . ') VALUES (' . $values . ')';
        $statement = $this->dbHelper->execute($query, array_values($order));
        if ($statement == null)
        {
            $this->error = $this->dbHelper->getError();
        }
        return $statement;
    }

    /**
     * Prepares and execute an SELECT statement to select all data from the table
     *
     * @return stdClass[]    Array of stdClass for each row
     */
    public function getAll()
    {
        $query = 'SELECT * FROM `' . $this->table . '`';
        return $this->dbHelper->execute($query)->fetchAll(PDO::FETCH_CLASS, 'stdClass');
    }

    /**
     * Get last inserted ID from the table
     *
     * @param string $name Database table name
     *
     * @return string
     */
    public function lastInsertId(string $name = NULL)
    {
        return $this->dbHelper->getPdo()->lastInsertId($name);
    }

    /**
     * Excludes non-existent fields based on fields map
     *
     * @param array $data Associated array of fileds and values
     *
     * @return array
     */
    protected function filter($data)
    {
        $order = array();
        if (!is_array($data))
        {
            return $order;
        }
        foreach ($data as $field => $value)
        {
            if (isset($this->tableFieldsMap[$field]))
            {
                $order[$this->tableFieldsMap[$field]] = $value;
            }
        }
        return $order;
    }

    /**
     * Public access for error of execution
     *
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Delete all rows from specific table
     *
     * @return int
     */
    public function deleteAll()
    {
        $query = 'DELETE FROM `' . $this->table . '`';
        return $this->dbHelper->execute($query)->rowCount();
    }

}
