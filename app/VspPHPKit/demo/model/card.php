<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Card Model
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class ModelCard extends ModelAbstract
{

    /**
     * Constructor for ModelCard
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'customercard';
        $this->tableFieldsMap = array(
            'id' => 'id',
            'created' => 'created',
            'last4digits' => 'last4digits',
            'modified' => 'modified',
            'token' => 'token',
            'customer_id' => 'customer_id'
        );
    }

    /**
     * Get all tokens by customer ID
     *
     * @param array $customerId
     *
     * @return array
     */
    public function getAllTokensByCustomerId($customerId)
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE customer_id = ?';
        try
        {
            return $this->dbHelper->execute($query, array($customerId))->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Exception $ex)
        {
            SagepayUtil::log($ex->getMessage());
            return array();
        }
    }

}
