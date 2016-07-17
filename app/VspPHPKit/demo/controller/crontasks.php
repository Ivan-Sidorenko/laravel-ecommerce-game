<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Controller for cron tasks
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class ControllerCrontasks
{

    /**
     * Define HelperDatabase
     *
     * @var HelperDatabase
     */
    protected $dbHelper;

    /**
     * Initialization default variables
     *
     * @throws SagepayApiException
     */
    public function __construct()
    {
        if (!defined('CLIENT_EXEC'))
        {
            throw new SagepayApiException('No direct script access.');
        }
        $this->dbHelper = HelperDatabase::getInstance();
    }

    /**
     * Run cancel payments
     */
    public function run()
    {
        $this->cancelPayments();
    }

    /**
     * Cancel payments automatically after 90 days (30 days for Maestro)
     */
    protected function cancelPayments()
    {
        $i = 0;
        $sql = "SELECT `vendorTxCode` FROM payment WHERE status = ? AND " .
                "((cardType='MAESTRO' AND created <= ?) OR created <= ?)";
        $params = array(SAGEPAY_REMOTE_STATUS_AUTHENTICATED, date('Y-m-d H:i:s', strtotime('-30 days')), date('Y-m-d H:i:s',
                    strtotime('-90 days')));
        $cancelStatus = array('Status' => SAGEPAY_REMOTE_STATUS_CANCELLED);

        $payment = new ModelPayment();
        $paymentStm = $this->dbHelper->execute($sql, $params);
        if (!$paymentStm)
        {
            exit('Invalid query');
        }
        $payments = $paymentStm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($payments as $row)
        {
            if ($payment->update($row['vendorTxCode'], $cancelStatus))
            {
                $i++;
            }
        }
        printf('Updated %d payments', $i);
    }

}
