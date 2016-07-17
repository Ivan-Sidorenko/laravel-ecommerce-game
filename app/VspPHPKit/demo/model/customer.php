<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Customer Model
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class ModelCustomer extends ModelAbstract
{

    /**
     * Constructor for ModelCustomer
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'customer';
        $this->tableFieldsMap = array(
            'id' => 'id',
            'created' => 'created',
            'email' => 'email',
            'hashedPassword' => 'hashedPassword',
            'modified' => 'modified',
        );
    }

}
