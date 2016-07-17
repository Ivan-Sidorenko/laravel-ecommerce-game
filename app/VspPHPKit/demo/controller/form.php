<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Controller for FORM integration method
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class ControllerForm extends ControllerAbstract
{

    /**
     * Initialization default variables
     */
    public function __construct()
    {
        $this->integrationType = SAGEPAY_FORM;
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
     * Action index front page for form payment
     */
    public function actionIndex()
    {
        $env = $this->sagepayConfig->getEnv();
        $siteFqdn = $this->sagepayConfig->getSiteFqdn();
        $encryptionPassword = $this->sagepayConfig->getFormEncryptionPassword($env);

        // Render front page for form payment
        $view = new HelperView('form/index');
        $view->setData(array(
            'integrationType' => $this->integrationType,
            'fullUrl' => url(array('form'), $siteFqdn),
            'siteFqdn' => $siteFqdn,
            'env' => $env,
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'currency' => $this->sagepayConfig->getCurrency(),
            'purchaseUrl' => $this->sagepayConfig->getPurchaseUrl('form', $env),
            'isEncryptionPasswordOk' => (!empty($encryptionPassword) && (strlen($encryptionPassword) == 16))
        ));
        $view->render();
    }

    /**
     * Action confirmation page for form payment
     */
    public function actionConfirm()
    {
        parent::actionConfirm();
        $api = $this->buildApi();

        $details = HelperCommon::getStore('details');
        $api->updateData($details);

        // fill items with product details
        $items = array();
        foreach ($api->getBasket()->getItems() as $item)
        {
            $items[] = array(
                'productUrlImage' => $this->getProductUrlImage($item->getDescription()),
                'description' => $item->getDescription(),
                'quantity' => $item->getQuantity(),
                'unitGrossAmount' => number_format($item->getUnitGrossAmount(), 2),
                'totalGrossAmount' => number_format($item->getTotalGrossAmount(), 2),
            );
        }

        $env = $this->sagepayConfig->getEnv();

        // Render confirm page for form payment
        $view = new HelperView('form/confirm');
        $view->setData(array(
            'basket' => array(
                'items' => $items,
                'deliveryGrossPrice' => number_format($api->getBasket()->getDeliveryGrossAmount(), 2),
                'totalGrossPrice' => number_format($api->getBasket()->getAmount(), 2),
            ),
            'env' => $env,
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'currency' => $this->sagepayConfig->getCurrency(),
            'purchaseUrl' => $this->sagepayConfig->getPurchaseUrl('form', $env),
            'request' => $api->createRequest(),
            'displayQueryString' => htmlspecialchars(rawurldecode(utf8_encode($api->getQueryData()))),
            'details' => $details,
        ));
        $view->render();
    }

    /**
     * Action success status of transaction for form payment
     */
    public function actionSuccess()
    {
        // Clear session
        HelperCommon::clearStore(array('details', 'extra', 'isDeliverySame'));
        $view = new HelperView('form/result');
        $view->setData($this->_resultData(true));
        $view->render();
    }

    /**
     * Action failure status of transaction for form payment
     */
    public function actionFailure()
    {
        // Clear all session
        HelperCommon::clearStore(array('details', 'extra', 'isDeliverySame'));
        $view = new HelperView('form/result');
        $view->setData($this->_resultData());
        $view->render();
    }

    /**
     * Return data for result query
     *
     * @param boolean $isSuccess
     * @return array
     * @throws SagepayApiException
     */
    private function _resultData($isSuccess = false)
    {
        $formPassword = $this->sagepayConfig->getFormPassword();
        $env = $this->sagepayConfig->getEnv();
        $crypt = filter_input(INPUT_GET, 'crypt');
        $decrypt = SagepayUtil::decryptAes($crypt, $formPassword[$env]);
        $decryptArr = SagepayUtil::queryStringToArray($decrypt);
        if (!$decrypt || empty($decryptArr))
        {
            throw new SagepayApiException('Invalid crypt input');
        }

        $helperMessage = new HelperMessage();
        $basket = $this->getBasketFromProducts();
        $items = array();
        // Get products from basket
        if ($basket)
        {
            foreach ($basket->getItems() as $item)
            {
                $items[] = array(
                    'productUrlImage' => $this->getProductUrlImage($item->getDescription()),
                    'description' => $item->getDescription(),
                    'quantity' => $item->getQuantity(),
                );
            }
        }
        return array(
            'env' => $env,
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'basket' => array(
                'items' => $items,
            ),
            'decrypt' => $decryptArr,
            'currency' => $this->sagepayConfig->getCurrency(),
            'isSuccess' => $isSuccess,
            'message' => $helperMessage->getMessage($decryptArr['Status']),
            'res' => array(
                'vpsTxId' => $decryptArr['VPSTxId'],
                'txAuthNo' => isset($decryptArr['TxAuthNo']) ? $decryptArr['TxAuthNo'] : '',
                'Surcharge' => isset($decryptArr['Surcharge']) ? $decryptArr['Surcharge'] : '',
                'BankAuthCode' => isset($decryptArr['BankAuthCode']) ? $decryptArr['BankAuthCode'] : '',
                'DeclineCode' => isset($decryptArr['DeclineCode']) ? $decryptArr['DeclineCode'] : '',
                'GiftAid' => isset($decryptArr['GiftAid']) && $decryptArr['GiftAid'] == 1,
                'avsCv2' => isset($decryptArr['AVSCV2']) ? $decryptArr['AVSCV2'] : '',
                'addressResult' => isset($decryptArr['AddressResult']) ? $decryptArr['AddressResult'] : '',
                'postCodeResult' => isset($decryptArr['PostCodeResult']) ? $decryptArr['PostCodeResult'] : '',
                'cv2Result' => isset($decryptArr['CV2Result']) ? $decryptArr['CV2Result'] : '',
                '3DSecureStatus' => isset($decryptArr['3DSecureStatus']) ? $decryptArr['3DSecureStatus'] : '',
                'CAVV' => isset($decryptArr['CAVV']) ? $decryptArr['CAVV'] : '',
                'cardType' => isset($decryptArr['CardType']) ? $decryptArr['CardType'] : '',
                'last4Digits' => isset($decryptArr['Last4Digits']) ? $decryptArr['Last4Digits'] : '',
                'expiryDate' => isset($decryptArr['ExpiryDate']) ? $decryptArr['ExpiryDate'] : '',
                'addressStatus' => isset($decryptArr['AddressStatus']) ? $decryptArr['AddressStatus'] : '',
                'payerStatus' => isset($decryptArr['PayerStatus']) ? $decryptArr['PayerStatus'] : ''
            )
        );
    }

}
