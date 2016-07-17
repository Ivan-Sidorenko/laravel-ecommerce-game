<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Customer Product
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class ModelProduct extends ModelAbstract
{

    /**
     * Constructor for ModelCustomer
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'product';
        $this->tableFieldsMap = array(
            'id' => 'id',
            'title' => 'title',
            'price' => 'price',
            'sku' => 'sku',
            'code' => 'code',
            'tax' => 'tax',
            'image' => 'image',
        );
    }

}
