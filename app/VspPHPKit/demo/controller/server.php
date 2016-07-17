<?php

defined('DEMO_PATH') || exit('No direct script access.');

include_once DEMO_PATH . '/controller/server_direct.php';

/**
 * Controller for SERVER integration method
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class ControllerServer extends ControllerServerDirect
{

    /**
     * Fey used in the session to store the profile mode
     *
     * @var string
     */
    const SESSION_KEY_PROFILE = "sagepay_server_profile";

    /**
     * Initialization default variables
     */
    public function __construct()
    {
        $this->integrationType = SAGEPAY_SERVER;
    }

    /**
     * Hook that is trigged before actions
     *
     * @see ControllerAbstract::before()
     * @return boolean
     */
    public function before()
    {
        $this->purchaseUrl = $this->sagepayConfig->getPurchaseUrl($this->integrationType);
        return true;
    }

    /**
     * Action index front page for server payment
     */
    public function actionIndex()
    {
        // Check existing profile
        if (filter_input(INPUT_GET, 'profile'))
        {
            // Check correctness for profile
            if (in_array(filter_input(INPUT_GET, 'profile'),
                            array(SAGEPAY_SERVER_PROFILE_LOW, SAGEPAY_SERVER_PROFILE_NORMAL)))
            {
                HelperCommon::setStore(self::SESSION_KEY_PROFILE, filter_input(INPUT_GET, 'profile'));
                $this->redirect('server', 'welcome');
            }
        }

        // Render choose profile page for server payment
        $view = new HelperView('server/choose_profile');
        $view->setData(array(
            'normalProfileUrl' => url('server') . '?profile=' . SAGEPAY_SERVER_PROFILE_NORMAL,
            'lowProfileUrl' => url('server') . '?profile=' . SAGEPAY_SERVER_PROFILE_LOW,
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
        ));
        $view->render();
    }

    /**
     * Action welcome page for server payment
     * @see ControllerServerDirect::actionWelcome()
     */
    public function actionWelcome()
    {
        if (!in_array(HelperCommon::getStore(self::SESSION_KEY_PROFILE),
                        array(SAGEPAY_SERVER_PROFILE_LOW, SAGEPAY_SERVER_PROFILE_NORMAL)))
        {
            $this->redirect('server');
        }

        parent::actionWelcome();
    }

    /**
     * Action register page for server payment
     */
    public function actionRegister()
    {
        $profile = HelperCommon::getStore(self::SESSION_KEY_PROFILE);
        $this->sagepayConfig->setServerProfile($profile);

        $api = $this->buildApi();
        $api->setVpsServerUrl($this->purchaseUrl);
        $result = $api->createRequest();

        if ($result['Status'] != SAGEPAY_REMOTE_STATUS_OK)
        {
            $this->redirect('server', 'confirm', array('error' => base64_encode($result['StatusDetail'])));
        }

        $data = array_merge($api->getData(), $result);

        // Insert Payment in db
        $payment = new ModelPayment();
        $payment->insert($data);

        // Clear all session not products
        HelperCommon::clearStore(array('sagepay_server_profile', 'isDeliverySame', 'details', 'extra', 'VendorTxCode'));
        if ($profile == SAGEPAY_SERVER_PROFILE_LOW)
        {
            HelperCommon::setStore('txData', $result);
            $this->redirect('server', 'low-profile');
        }

        header('Location: ' . $result['NextURL']);
        exit();
    }

    /**
     * Notify page, used for server ONLY
     */
    public function actionNotify()
    {
        $payment = new ModelPayment();
        $result = $payment->getByVendorTxCode(filter_input(INPUT_POST, 'VendorTxCode'));
        $siteFqdn = $this->sagepayConfig->getSiteFqdn();

        SagepayUtil::log('NOTIFY:' . PHP_EOL . json_encode(filter_input_array(INPUT_POST)));
        $vtxData = filter_input_array(INPUT_POST);
        if (in_array(filter_input(INPUT_POST, 'Status'),
                        array(SAGEPAY_REMOTE_STATUS_OK, SAGEPAY_REMOTE_STATUS_AUTHENTICATED, SAGEPAY_REMOTE_STATUS_REGISTERED)))
        {
            $surcharge = floatval(filter_input(INPUT_POST, 'Surcharge', FILTER_VALIDATE_FLOAT));
            $vtxData['Amount'] = $result['amount'] + $surcharge;
            if (filter_input(INPUT_POST, 'TxType') == SAGEPAY_REMOTE_STATUS_PAYMENT)
            {
                $vtxData['CapturedAmount'] = $vtxData['Amount'];
            }
            $data = array(
                "Status" => SAGEPAY_REMOTE_STATUS_OK,
                "RedirectURL" => $siteFqdn . 'server/success?vtx=' . filter_input(INPUT_POST, 'VendorTxCode'),
                "StatusDetail" => 'The transaction was successfully processed.'
            );
        }
        else
        {
            $data = array(
                "Status" => SAGEPAY_REMOTE_STATUS_OK,
                "RedirectURL" => $siteFqdn . 'server/failure?vtx=' . filter_input(INPUT_POST, 'VendorTxCode'),
                "StatusDetail" => filter_input(INPUT_POST, 'StatusDetail')
            );
        }
        $vtxData['AllowGiftAid'] = filter_input(INPUT_POST, 'GiftAid');
        $payment->update(filter_input(INPUT_POST, 'VendorTxCode'), $vtxData);
        echo SagepayUtil::arrayToQueryString($data, "\n");
    }

    /**
     * Action success page for server payment
     */
    public function actionSuccess()
    {
        // Clear all session
        HelperCommon::clearStore(array('sagepay_server_profile', 'isDeliverySame', 'details', 'extra', 'VendorTxCode'));

        $view = new HelperView('server/result');
        $view->setData($this->getPaymentResultData(true));
        $view->render();
    }

    /**
     * Action failure page for server payment
     */
    public function actionFailure()
    {
        // Clear all session
        HelperCommon::clearStore(array('sagepay_server_profile', 'isDeliverySame', 'details', 'extra', 'VendorTxCode'));

        $view = new HelperView('server/result');
        $view->setData($this->getPaymentResultData());
        $view->render();
    }

    /**
     * Action low profile page for server payment
     */
    public function actionLowProfile()
    {
        $view = new HelperView('server/low_profile');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'request' => HelperCommon::getStore('txData'),
        ));
        $view->render();
    }

}
