<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Payment Model
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class ModelPayment extends ModelAbstract
{

    /**
     * Constructor for ModelPayment
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'payment';
        $this->tableFieldsMap = array(
            'VendorTxCode' => 'vendorTxCode',
            'AddressResult' => 'addressResult',
            'AddressStatus' => 'addressStatus',
            'Amount' => 'amount',
            'AVSCV2' => 'avsCv2',
            'Basket' => 'basket',
            'BasketXML' => 'basketXml',
            'BillingAddress1' => 'billingAddress1',
            'BillingAddress2' => 'billingAddress2',
            'BillingCity' => 'billingCity',
            'BillingCountry' => 'billingCountry',
            'BillingFirstnames' => 'billingFirstnames',
            'BillingPhone' => 'billingPhone',
            'BillingPostCode' => 'billingPostCode',
            'BillingState' => 'billingState',
            'BillingSurname' => 'billingSurname',
            'CapturedAmount' => 'capturedAmount',
            'CardType' => 'cardType',
            'CAVV' => 'cavv',
            'CreateToken' => 'createToken',
            'created' => 'created',
            'Currency' => 'currency',
            'CustomerEMail' => 'customerEmail',
            'CV2Result' => 'cv2Result',
            'DeliveryAddress1' => 'deliveryAddress1',
            'DeliveryAddress2' => 'deliveryAddress2',
            'DeliveryCity' => 'deliveryCity',
            'DeliveryCountry' => 'deliveryCountry',
            'DeliveryFirstnames' => 'deliveryFirstnames',
            'DeliveryPhone' => 'deliveryPhone',
            'DeliveryPostCode' => 'deliveryPostCode',
            'DeliveryState' => 'deliveryState',
            'DeliverySurname' => 'deliverySurname',
            'ExpiryDate' => 'expiryDate',
            'AllowGiftAid' => 'giftAid',
            'Last4Digits' => 'last4Digits',
            'modified' => 'modified',
            'PayerId' => 'payerId',
            'PayerStatus' => 'payerStatus',
            'PostCodeResult' => 'postCodeResult',
            'RelatedVendorTxCode' => 'relatedVendorTxCode',
            'SecurityKey' => 'securityKey',
            'Status' => 'status',
            'StatusDetail' => 'statusMessage',
            'Surcharge' => 'surcharge',
            '3DSecureStatus' => 'threeDSecureStatus',
            'Token' => 'token',
            'TxType' => 'transactionType',
            'TxAuthNo' => 'txAuthNo',
            'VPSTxId' => 'vpsTxId',
            'BankAuthCode' => 'bankAuthCode',
            'DeclineCode' => 'declineCode',
            'FraudResponse' => 'fraudResponse'
        );
    }

    /**
     * Update payment data by vendor transaction code
     *
     * @param string $vendorTxCode
     * @param array $data Associated array of fileds and values
     *
     * @return PDOStatement
     */
    public function update($vendorTxCode, $data)
    {
        $order = $this->filter($data);
        unset($order['vendorTxCode']);
        $values = array();
        foreach (array_keys($order) as $value)
        {
            $values[] = sprintf('%s = ?', $value);
        }

        $query = 'UPDATE `' . $this->table . '` SET ' . implode(', ', $values) . ' WHERE vendorTxCode = ?';
        $order['vendorTxCode'] = $vendorTxCode;
        return $this->dbHelper->execute($query, array_values($order));
    }

    /**
     * Get payment data by vendor transaction code
     *
     * @param string $vendorTxCode
     *
     * @return string[] Payment model as associative array
     */
    public function getByVendorTxCode($vendorTxCode)
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE `vendorTxCode` = ?';
        $result = $this->dbHelper->execute($query, array($vendorTxCode));
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get related amount data by vendor transaction code
     *
     * @param string $vendorTxCode
     *
     * @return float
     */
    public function getRelatedAmount($vendorTxCode)
    {
        $query = 'SELECT SUM(`amount`) as related_amount FROM `' . $this->table . '` WHERE `relatedVendorTxCode` = ? '
        . 'AND `status` = ? AND `transactionType` = ? GROUP BY `relatedVendorTxCode`';
        $result = $this->dbHelper->execute($query, array($vendorTxCode, SAGEPAY_REMOTE_STATUS_OK, SAGEPAY_TXN_REFUND));
        if ($result !== null)
        {
            $summ = $result->fetch(PDO::FETCH_ASSOC);
            return floatval($summ['related_amount']);
        }
        return 0.0;
    }

}
