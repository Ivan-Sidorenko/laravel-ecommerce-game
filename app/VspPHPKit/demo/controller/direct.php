<?php

defined('DEMO_PATH') || exit('No direct script access.');

include_once DEMO_PATH . '/controller/server_direct.php';

/**
 * Controller for DIRECT integration method
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class ControllerDirect extends ControllerServerDirect
{
    /**
     * Key used in the session to store the profile mode
     *
     * @var string
     */

    const SESSION_KEY_PROFILE = "sagepay_direct_profile";

    /**
     * Initialization integration type
     */
    public function __construct()
    {
        $this->integrationType = SAGEPAY_DIRECT;
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
     * Action index page for direct payment
     */
    public function actionIndex()
    {
        $this->actionWelcome();
    }

    /**
     * Action basket checkout page for direct payment
     */
    public function actionBasketCheckout()
    {
        $this->checkProducts(null, true);
        // Check if form was submitted
        if (count(filter_input_array(INPUT_POST)))
        {
            HelperCommon::setStore('checkoutType', filter_input(INPUT_POST, 'checkoutType'));
            $this->redirect('direct', 'details');
        }
        $basket = $this->getBasketFromProducts();

        $basket->setDeliveryNetAmount(1.5);
        $basket->setDescription('DVDs from Sagepay Demo Page');
        $items = array();
        // Get products from basket
        foreach ($basket->getItems() as $item)
        {
            $items[] = array(
                'urlImage' => $this->getProductUrlImage($item->getDescription()),
                'description' => $item->getDescription(),
                'quantity' => $item->getQuantity(),
                'unitGrossAmount' => number_format($item->getUnitGrossAmount(), 2),
                'totalGrossAmount' => number_format($item->getTotalGrossAmount(), 2),
            );
        }
        $currency = $this->sagepayConfig->getCurrency();
        // render view basket checkout
        $view = new HelperView('direct/basket_checkout');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'currency' => $this->sagepayConfig->getCurrency(),
            'deliveryGrossPrice' => number_format($basket->getDeliveryGrossAmount(), 2) . ' ' . $currency,
            'totalGrossPrice' => number_format($basket->getAmount(), 2) . ' ' . $currency,
            'basket' => array(
                'items' => $items
            )
        ));
        $view->render();
    }

    /**
     * Action card page for direct payment
     */
    public function actionCard()
    {
        $message = '';
        // Check if form was submitted
        if (count(filter_input_array(INPUT_POST)))
        {
            $useToken = filter_input(INPUT_POST, 'useToken');
            $giftAid = filter_input(INPUT_POST, 'giftAid');

            $card = array(
                'cardType' => filter_input(INPUT_POST, 'cardType'),
                'cardNumber' => filter_input(INPUT_POST, 'cardNumber'),
                'cardHolder' => filter_input(INPUT_POST, 'cardHolder'),
                'startDate' => filter_input(INPUT_POST, 'startDate'),
                'expiryDate' => filter_input(INPUT_POST, 'expiryDate'),
                'cv2' => filter_input(INPUT_POST, 'cv2'),
                'giftAid' => !!$giftAid,
            );
            $cardDetails = new SagepayCardDetails();
            $this->_populateCardDetails($cardDetails, $card);

            // Check cardType
            if ($card['cardType'] == 'PAYPAL')
            {
                $errors = array();
            }
            else
            {
                $errors = $cardDetails->validate();
            }
            $hMessage = new HelperMessage();
            $message = $hMessage->getAllMessages($errors, array(
                'cardNumber' => 'Card Number',
                'cardHolder' => 'Card Holder Name',
                'startDate' => 'Start Date',
                'expiryDate' => 'Expiry Date',
                'cv2' => 'Card Verification Value',
            ));
            // Check if card data was failed
            if ($errors)
            {
                $this->error = true;
                $message = "Sorry, the following problems were found: " . $message;
            }
            else
            {
                if ($useToken)
                {
                    $account = HelperCommon::getStore('account');
                    $sagepayToken = new SagepayToken($this->sagepayConfig);
                    $token = $sagepayToken->register($card);

                    if (!$token)
                    {
                        $this->helperError('Card Details are invalid ', url(array('direct', 'card')));
                        exit;
                    }

                    ModelAbstract::factory('Card')->insert(array(
                        'last4digits' => SagepayUtil::getLast4Digits(filter_input(INPUT_POST, 'cardNumber')),
                        'token' => $token,
                        'customer_id' => $account['id']
                    ));

                    $account['token'] = $token;
                    HelperCommon::setStore('account', $account);
                    $card = array(
                        'cardType' => '',
                        'cardNumber' => '',
                        'cardHolder' => '',
                        'startDate' => '',
                        'expiryDate' => '',
                        'cv2' => filter_input(INPUT_POST, 'cv2'),
                        'giftAid' => $giftAid,
                    );
                }
                HelperCommon::setStore('card', $card);
                $this->redirect('direct', 'confirm');
            }
        }
        // render view card
        $view = new HelperView('direct/card');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'error' => $this->error,
            'message' => $message,
            'allowGiftAid' => $this->sagepayConfig->getAllowGiftAid()
        ));
        $view->render();
    }

    /**
     * Action card token page for direct payment
     */
    public function actionCardToken()
    {
        $message = '';
        // Check if form was submitted
        if (count(filter_input_array(INPUT_POST)))
        {
            $giftAid = !!filter_input(INPUT_POST, 'giftAid');
            $rules = array(
                'cv2' => array(
                    array('notEmpty'),
                ),
            );
            $card = array(
                'cardType' => '',
                'cardNumber' => '',
                'cardHolder' => '',
                'startDate' => '',
                'expiryDate' => '',
                'cv2' => filter_input(INPUT_POST, 'cv2'),
                'giftAid' => $giftAid,
            );
            $errors = $this->validate($rules, $card);
            $hMessage = new HelperMessage();
            $message = $hMessage->getAllMessages($errors, array(
                'cv2' => 'Card Verification Value',
            ));
            // Check if card token was failed
            if ($errors)
            {
                $this->error = true;
                $message = "Sorry, the following problems were found: " . $message;
            }
            else
            {
                HelperCommon::setStore('card', $card);
                $this->redirect('direct', 'confirm');
            }
        }
        // render view card token
        $view = new HelperView('direct/card_token');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'error' => $this->error,
            'message' => $message,
            'allowGiftAid' => $this->sagepayConfig->getAllowGiftAid()
        ));
        $view->render();
    }

    /**
     * Action register page for direct payment
     */
    public function actionRegister()
    {
        $api = $this->buildApi();
        $card = HelperCommon::getStore('card');
        $siteFqdn = $this->sagepayConfig->getSiteFqdn();
        // Check cardType
        if ($card['cardType'] == 'PAYPAL')
        {
            $api->setIntegrationMethod(SAGEPAY_PAYPAL);
            
            $this->sagepayConfig->setPaypalCallbackUrl(url('direct/paypal-response', $siteFqdn));
        }
        $account = HelperCommon::getStore('account');
        $api->setPaneValues($card + $account);

        $api->setVpsDirectUrl($this->purchaseUrl);
        $response = $api->createRequest();
        $data = $api->getData();
        $data += $response;

        // Insert in database
        $payment = new ModelPayment();
        $payment->insert($data);

        // Redirect
        $vtxQuery = array('vtx' => $data['VendorTxCode']);
        if ($response['Status'] == SAGEPAY_REMOTE_STATUS_PAYPAL_REDIRECT)
        {
            header('Location: ' . $response['PayPalRedirectURL']);
            exit;
        }
        else if ($response['Status'] == "3DAUTH")
        {
            $threeDSecure = array(
                'MD' => $response['MD'],
                'ACSURL' => $response['ACSURL'],
                'PaReq' => $response['PAReq'],
                'TermUrl' => url(array('direct', 'three-d-secure-result'), $siteFqdn) . '?' . SagepayUtil::arrayToQueryString($vtxQuery)
            );
            HelperCommon::setStore('3DAUTH', $threeDSecure);
            $this->redirect('direct', 'three-d-secure', $vtxQuery);
        }
        else if (in_array($response['Status'], array(SAGEPAY_REMOTE_STATUS_OK, SAGEPAY_REMOTE_STATUS_REGISTERED)))
        {
            if ($data['TxType'] == SAGEPAY_REMOTE_STATUS_PAYMENT)
            {
                $surcharge = isset($response['Surcharge']) ? floatval($response['Surcharge']) : 0.0;
                $paymentTx = array(
                    'CapturedAmount' => floatval($data['Amount']) + $surcharge,
                    'Amount' => floatval($data['Amount']) + $surcharge
                );
                $payment->update($data['VendorTxCode'], $paymentTx);
            }
            $this->redirect('direct', 'success', $vtxQuery);
        }

        $this->redirect('direct', 'failure', $vtxQuery);
    }

    /**
     * Action 3D secure page for direct payment
     */
    public function actionThreeDSecure()
    {
        $threeDSecure = HelperCommon::getStore('3DAUTH');
        HelperCommon::clearStore('3DAUTH');
        if (empty($threeDSecure))
        {
            $this->redirect('direct', 'failure', filter_input_array(INPUT_GET));
        }

        $purchaseUrl = $threeDSecure['ACSURL'];
        unset($threeDSecure['ACSURL']);
        $view = new HelperView('direct/secure3d');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'purchaseUrl' => $purchaseUrl,
            'request' => array(), //$api->createRequest(),
            'threeDSecure' => $threeDSecure
        ));
        $view->render();
    }

    /**
     * Action for 3D secure result for direct payment
     */
    public function actionThreeDSecureResult()
    {
        $modelPayment = new ModelPayment();
        $result = SagepayCommon::requestPost($this->sagepayConfig->getPurchaseUrl('direct3d'), filter_input_array(INPUT_POST));

        // Check transaction status
        $status = 'failure';
        if (in_array($result['Status'], array(SAGEPAY_REMOTE_STATUS_AUTHENTICATED, SAGEPAY_REMOTE_STATUS_REGISTERED, SAGEPAY_REMOTE_STATUS_OK)))
        {
            $data = $modelPayment->getByVendorTxCode(filter_input(INPUT_GET, 'vtx'));
            $surcharge = isset($result['Surcharge']) ? floatval($result['Surcharge']) : 0.0;
            $result['Amount'] = floatval($data['amount']) + $surcharge;

            // Register the Captured ammount for Payment Transaction Type
            if ($data['transactionType'] == SAGEPAY_TXN_PAYMENT)
            {
                $result['CapturedAmount'] = $result['Amount'];
            }
            $status = 'success';
        }
        $modelPayment->update(filter_input(INPUT_GET, 'vtx'), $result);
        $this->redirect('direct', $status, filter_input_array(INPUT_GET));
    }

    /**
     * Action PayPal response for direct payment
     */
    public function actionPaypalResponse()
    {
        if (!filter_input(INPUT_GET, 'vtx'))
        {
            $this->redirect('direct', 'failure');
        }

        $modelPayment = new ModelPayment();
        $paymentTx = $modelPayment->getByVendorTxCode(filter_input(INPUT_GET, 'vtx'));

        $data = array(
            'VPSProtocol' => $this->sagepayConfig->getProtocolVersion(),
            'TxType' => SAGEPAY_TXN_COMPLETE,
            'VPSTxId' => filter_input(INPUT_POST, 'VPSTxId'),
            'Amount' => number_format($paymentTx['amount'], 2),
            'Accept' => (filter_input(INPUT_POST, 'Status') == SAGEPAY_REMOTE_STATUS_PAYPAL_OK) ? 'YES' : 'NO'
        );

        $result = SagepayCommon::requestPost($this->sagepayConfig->getPurchaseUrl('paypal'), $data);
        $paymentDetails = array_merge(filter_input_array(INPUT_POST), $result);

        $status = 'failure';
        if (($result['Status'] == SAGEPAY_REMOTE_STATUS_OK) || ($result['Status'] == SAGEPAY_REMOTE_STATUS_REGISTERED))
        {
            $status = 'success';
            $surcharge = isset($result['Surcharge']) ? floatval($result['Surcharge']) : 0.0;
            $paymentDetails['Amount'] = floatval($paymentTx['amount']) + $surcharge;
            if (($result['Status'] == SAGEPAY_REMOTE_STATUS_OK && $paymentTx['transactionType'] !== SAGEPAY_TXN_DEFERRED))
            {
                $paymentDetails['CapturedAmount'] = $paymentDetails['Amount'];
            }
        }
        $modelPayment->update(filter_input(INPUT_GET, 'vtx'), $paymentDetails);
        $this->redirect('direct', $status, filter_input_array(INPUT_GET));
    }

    /**
     * Action success for direct payment
     */
    public function actionSuccess()
    {

        // Clear all session
        HelperCommon::clearStore(array('isDeliverySame', 'details', 'extra', 'VendorTxCode'));

        $view = new HelperView('direct/result');
        $view->setData($this->getPaymentResultData(true));
        $view->render();
    }

    /**
     * Action failure for direct payment
     */
    public function actionFailure()
    {
        // Clear all session
        HelperCommon::clearStore(array('isDeliverySame', 'details', 'extra', 'VendorTxCode'));

        $view = new HelperView('direct/result');
        $view->setData($this->getPaymentResultData());
        $view->render();
    }

    /**
     * Populate card details
     *
     * @param SagepayCardDetails $cardDetails
     * @param array $card
     */
    private function _populateCardDetails(SagepayCardDetails $cardDetails, array $card)
    {
        foreach ($card as $key => $value)
        {
            $cardDetails->$key = $value;
        }
    }

}
