<?php defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Controller for Administrator panel
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class ControllerAdmin extends ControllerAbstract
{

    /**
     * Define rules
     *
     * @var array
     */
    protected $_rules = array(
        'VendorTxCode' => array(
            array('notEmpty'),
            array('maxLength', array(40)),
        ),
        'Description' => array(
            array('notEmpty'),
            array('maxLength', array(100)),
        ),
        'Amount' => array(
            array('regex', array("/^[0-9]+(\.[0-9]{1,2})*$/")),
        ),
        'ReleaseAmount' => array(
            array('regex', array("/^[0-9]+(\.[0-9]{1,2})*$/")),
        ),
        'ApplyAVSCV2' => array(
            array('regex', array("/[0-9]/")),
        ),
        'CV2' => array(
            array('maxLength', array(4)),
            array('minLength', array(3)),
        ),
    );

    /**
     * Action admin for payment
     */
    public function actionAdmin()
    {
        $payments = $this->dbHelper->execute('SELECT * FROM payment ORDER BY modified DESC');

        // Select token count for customers
        $sql = 'SELECT c . *, count( cc.id ) AS cards
            FROM customer c
            LEFT JOIN customercard cc ON c.id = cc.customer_id
            GROUP BY c.id';
        $customers = $this->dbHelper->execute($sql);

        // Render view admin
        $view = new HelperView('admin/admin');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'payments' => $payments,
            'customers' => $customers,
        ));
        $view->render();
    }

    /**
     * Action cancel the transaction
     */
    public function actionCancel()
    {
        // Check original VendorTxCode
        if (filter_input(INPUT_GET, 'origVtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_GET, 'origVtx'));

            $view = new HelperView('admin/cancel');
            $view->setData(array(
                'env' => $this->sagepayConfig->getEnv(),
                'vendorName' => $this->sagepayConfig->getVendorName(),
                'integrationType' => $this->integrationType,
                'result' => $result
            ));
            $view->render();
        }
        // Check if form was submitted
        else if (filter_input(INPUT_POST, 'origVtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_POST, 'origVtx'));

            $data = array(
                'VPSProtocol' => $this->sagepayConfig->getProtocolVersion(),
                'TxType' => SAGEPAY_TXN_CANCEL,
                'Vendor' => $this->sagepayConfig->getVendorName(),
                'VendorTxCode' => $result['vendorTxCode'],
                'VPSTxId' => $result['vpsTxId'],
                'SecurityKey' => $result['securityKey'],
            );

            $response = SagepayCommon::requestPost($this->sagepayConfig->getSharedUrl('cancel'), $data);

            // Check response status
            if ($response['Status'] == SAGEPAY_REMOTE_STATUS_OK)
            {
                $result['Status'] = SAGEPAY_REMOTE_STATUS_CANCELLED;
                $payment->update(filter_input(INPUT_POST, 'origVtx'), $result);
            }

            $query = array(
                'requestBody' => SagepayUtil::arrayToQueryString($data),
                'resultBody' => SagepayUtil::arrayToQueryString($response),
                'status' => $response['Status'],
                'command' => SAGEPAY_TXN_CANCEL
            );

            $this->redirect($this->integrationType, 'admin_result', $query);
        }
        else
        {
            $this->redirect($this->integrationType, 'admin');
        }
    }

    /**
     * Action cancels the transaction
     */
    public function actionVoid()
    {
        // Check original VendorTxCode
        if (filter_input(INPUT_GET, 'origVtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_GET, 'origVtx'));

            $view = new HelperView('admin/void');
            $view->setData(array(
                'env' => $this->sagepayConfig->getEnv(),
                'vendorName' => $this->sagepayConfig->getVendorName(),
                'integrationType' => $this->integrationType,
                'result' => $result
            ));
            $view->render();
            return;
        }

        // Check if form was submitted
        if (filter_input(INPUT_POST, 'origVtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_POST, 'origVtx'));

            $data = array(
                'VPSProtocol' => $this->sagepayConfig->getProtocolVersion(),
                'TxType' => SAGEPAY_TXN_VOID,
                'Vendor' => $this->sagepayConfig->getVendorName(),
                'VendorTxCode' => $result['vendorTxCode'],
                'VPSTxId' => $result['vpsTxId'],
                'SecurityKey' => $result['securityKey'],
                'TxAuthNo' => $result['txAuthNo']
            );

            $response = SagepayCommon::requestPost($this->sagepayConfig->getSharedUrl('void'), $data);

            $result += $response;
            if ($response['Status'] == SAGEPAY_REMOTE_STATUS_OK)
            {
                $result['Status'] = SAGEPAY_REMOTE_STATUS_VOIDED;
                $result['StatusDetail'] = 'The transaction was successfully processed.';
                $result['CapturedAmount'] = $result['amount'];
                $payment->update(filter_input(INPUT_POST, 'origVtx'), $result);
            }

            $query = array(
                'requestBody' => SagepayUtil::arrayToQueryString($data),
                'resultBody' => SagepayUtil::arrayToQueryString($response),
                'status' => $response['Status'],
                'command' => SAGEPAY_TXN_VOID
            );

            $this->redirect($this->integrationType, 'admin_result', $query);
        }
        $this->redirect($this->integrationType, 'admin');
    }

    /**
     * Action refund the transaction
     */
    public function actionRefund()
    {
        $result = array();
        $message = '';
        // Check original VendorTxCode
        if (filter_input(INPUT_GET, 'origVtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_GET, 'origVtx'));
            $origVtx = filter_input(INPUT_GET, 'origVtx');
        }
        // Check if form was submitted
        else if (filter_input(INPUT_POST, 'origVtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_POST, 'origVtx'));
            $origVtx = filter_input(INPUT_POST, 'origVtx');

            $data = array(
                'VPSProtocol' => $this->sagepayConfig->getProtocolVersion(),
                'TxType' => SAGEPAY_TXN_REFUND,
                'Vendor' => $this->sagepayConfig->getVendorName(),
                'VendorTxCode' => filter_input(INPUT_POST, 'VendorTxCode'),
                'Amount' => filter_input(INPUT_POST, 'Amount'),
                'Currency' => $this->sagepayConfig->getCurrency(),
                'Description' => filter_input(INPUT_POST, 'Description'),
                'RelatedVPSTxId' => $result['vpsTxId'],
                'RelatedVendorTxCode' => filter_input(INPUT_POST, 'origVtx'),
                'RelatedSecurityKey' => $result['securityKey'],
                'RelatedTxAuthNo' => $result['txAuthNo']
            );

            $maxAmount = $result['capturedAmount'] - (float) $payment->getRelatedAmount($origVtx);

            $this->_rules['Amount'][] = array('range', array(0, $maxAmount));
            $errors = $this->validate($this->_rules, $data);
            $hMessage = new HelperMessage();
            $message = $hMessage->getAllMessages($errors);
            // Check if refund was failed
            if (!$errors)
            {
                $response = SagepayCommon::requestPost($this->sagepayConfig->getSharedUrl('refund'), $data);

                if ($response['Status'] == SAGEPAY_REMOTE_STATUS_OK)
                {
                    $response['StatusDetail'] = 'REFUND transaction taken through Order Admin area';
                }

                $result = $this->ucFirstFields($result);
                unset($result['CapturedAmount']);
                $refundedTx = array_merge($result, $data, $response);

                $payment->insert($refundedTx);

                $query = array(
                    'requestBody' => SagepayUtil::arrayToQueryString($data),
                    'resultBody' => SagepayUtil::arrayToQueryString($response),
                    'status' => $response['Status'],
                    'command' => SAGEPAY_TXN_REFUND
                );

                $this->redirect($this->integrationType, 'admin_result', $query);
            }
            else
            {
                $this->error = true;
            }
        }

        // render refund page
        if (!empty($result))
        {
            $view = new HelperView('admin/refund');
            $view->setData(array(
                'env' => $this->sagepayConfig->getEnv(),
                'vendorName' => $this->sagepayConfig->getVendorName(),
                'integrationType' => $this->integrationType,
                'result' => $result,
                'refundVtx' => SagepayCommon::vendorTxCode(time(), SAGEPAY_TXN_REFUND, $this->sagepayConfig->getVendorName()),
                'alreadyRefundedAmount' => number_format($payment->getRelatedAmount($origVtx), 2),
                'val' => array(
                    'ok' => !$this->error,
                    'errorStatusString' => $message
                ),
            ));
            $view->render();
        }
        else
        {
            $this->redirect($this->integrationType, 'admin');
        }
    }

    /**
     * Action repeat the transaction
     */
    public function actionRepeat()
    {
        $message = '';
        $result = array();
        $deferred = false;
        // Check original VendorTxCode
        if (filter_input(INPUT_GET, 'origVtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_GET, 'origVtx'));
            $deferred = filter_input(INPUT_GET, 'deferred');
            $txType = $deferred == 'true' ? 'REPDEF' : 'REPEAT';
        }
        // Check if form was submitted
        else if (filter_input(INPUT_POST, 'origVtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_POST, 'origVtx'));

            $txType = SAGEPAY_TXN_REPEAT;
            if (filter_input(INPUT_POST, 'deferred') == 'true')
            {
                $txType = SAGEPAY_TXN_REPEATDEFERRED;
            }
            $data = array(
                'VPSProtocol' => $this->sagepayConfig->getProtocolVersion(),
                'TxType' => $txType,
                'Vendor' => $this->sagepayConfig->getVendorName(),
                'VendorTxCode' => filter_input(INPUT_POST, 'VendorTxCode'),
                'Amount' => filter_input(INPUT_POST, 'Amount'),
                'Currency' => $this->sagepayConfig->getCurrency(),
                'Description' => filter_input(INPUT_POST, 'Description'),
                'RelatedVPSTxId' => $result['vpsTxId'],
                'RelatedVendorTxCode' => $result['vendorTxCode'],
                'RelatedSecurityKey' => $result['securityKey'],
                'RelatedTxAuthNo' => $result['txAuthNo'],
                'DeliverySurname' => $result['deliverySurname'],
                'DeliveryFirstnames' => $result['deliveryFirstnames'],
                'DeliveryAddress1' => $result['deliveryAddress1'],
                'DeliveryAddress2' => $result['deliveryAddress2'],
                'DeliveryCity' => $result['deliveryCity'],
                'DeliveryPostCode' => $result['deliveryPostCode'],
                'DeliveryCountry' => $result['deliveryCountry'],
                'DeliveryState' => $result['deliveryState'],
                'DeliveryPhone' => $result['deliveryPhone'],
            );
            if (!empty($result['basketXml']))
            {
                $data['BasketXML'] = $result['basketXml'];
            }
            else
            {
                $data['Basket'] = $result['basket'];
            }

            $surchargeConfigs = $this->sagepayConfig->getSurcharges();
            if (!empty($surchargeConfigs))
            {
                $surcharge = new SagepaySurcharge();
                $surcharge->setSurcharges($surchargeConfigs);
                $data['SurchargeXML'] = $surcharge->export();
            }
            if (filter_input(INPUT_POST, 'cv2'))
            {
                $data['CV2'] = filter_input(INPUT_POST, 'cv2');
            }

            $errors = $this->validate($this->_rules, $data);
            $helperMessage = new HelperMessage();
            $message = $helperMessage->getAllMessages($errors);
            // Check if repeat was failed
            if (!$errors)
            {
                $response = SagepayCommon::requestPost($this->sagepayConfig->getSharedUrl('repeat'), $data);
                if ($response['Status'] == SAGEPAY_REMOTE_STATUS_OK)
                {
                    $response['StatusDetail'] = 'REPEAT transaction taken through Order Admin area';
                    if (filter_input(INPUT_POST, 'deferred') != 'true')
                    {
                        $response['CapturedAmount'] = $data['Amount'];
                    }
                }

                $result = $this->ucFirstFields($result);
                $repeatedTx = array_merge($result, $data, $response);
                if ($txType == SAGEPAY_TXN_REPEATDEFERRED)
                {
                    unset($repeatedTx['CapturedAmount']);
                }

                $payment->insert($repeatedTx);

                $query = array(
                    'requestBody' => SagepayUtil::arrayToQueryString($data),
                    'resultBody' => SagepayUtil::arrayToQueryString($response),
                    'status' => $response['Status'],
                    'command' => $txType
                );

                $this->redirect($this->integrationType, 'admin_result', $query);
            }
            $this->error = true;
        }

        // render repeat page
        if (!empty($result))
        {
            $view = new HelperView('admin/repeat');
            $view->setData(array(
                'env' => $this->sagepayConfig->getEnv(),
                'vendorName' => $this->sagepayConfig->getVendorName(),
                'integrationType' => $this->integrationType,
                'result' => $result,
                'newVtx' => SagepayCommon::vendorTxCode(time(), $txType, $this->sagepayConfig->getVendorName()),
                'val' => array(
                    'ok' => !$this->error,
                    'errorStatusString' => $message
                ),
                'deferred' => $deferred,
            ));
            $view->render();
        }
        else
        {
            $this->redirect($this->integrationType, 'admin');
        }
    }

    /**
     * Action release the transaction
     */
    public function actionRelease()
    {
        $message = '';
        $paymentTx = array();
        // Check original VendorTxCode
        if (filter_input(INPUT_GET, 'origVtx'))
        {
            $payment = new ModelPayment();
            $paymentTx = $payment->getByVendorTxCode(filter_input(INPUT_GET, 'origVtx'));
        }
        // Check if form was submitted
        else if (filter_input(INPUT_POST, 'origVtx'))
        {
            $payment = new ModelPayment();
            $paymentTx = $payment->getByVendorTxCode(filter_input(INPUT_POST, 'origVtx'));

            $data = array(
                'VPSProtocol' => $this->sagepayConfig->getProtocolVersion(),
                'TxType' => SAGEPAY_TXN_RELEASE,
                'Vendor' => $this->sagepayConfig->getVendorName(),
                'VendorTxCode' => $paymentTx['vendorTxCode'],
                'VPSTxId' => $paymentTx['vpsTxId'],
                'SecurityKey' => $paymentTx['securityKey'],
                'TxAuthNo' => $paymentTx['txAuthNo'],
                'ReleaseAmount' => filter_input(INPUT_POST, 'Amount')
            );
            $this->_rules['ReleaseAmount'][] = array('range', array(0, $paymentTx['amount']));
            $errors = $this->validate($this->_rules, $data);
            $hMessage = new HelperMessage();
            $message = $hMessage->getAllMessages($errors);

            // Check if release was failed
            if (!$errors)
            {
                $response = SagepayCommon::requestPost($this->sagepayConfig->getSharedUrl('release'), $data);

                $releasedTx = array(
                    'Status' => $response['Status'],
                    'StatusDetail' => '',
                );

                if ($response['Status'] == SAGEPAY_REMOTE_STATUS_OK)
                {
                    $releasedTx['CardType'] = $paymentTx['cardType'];
                    $releasedTx['StatusDetail'] = 'RELEASED amount=' . filter_input(INPUT_POST, 'Amount');
                    $releasedTx['CapturedAmount'] = $data['ReleaseAmount'];
                    $payment->update($paymentTx['vendorTxCode'], $releasedTx);
                }
                else if ($response['Status'] == SAGEPAY_REMOTE_STATUS_MALFORMED ||
                        $response['Status'] == SAGEPAY_REMOTE_STATUS_INVALID ||
                        $response['Status'] == SAGEPAY_REMOTE_STATUS_ERROR)
                {
                    $releasedTx['StatusDetail'] = 'The transaction has not completed.';
                }
                else if ($response['Status'] == SAGEPAY_REMOTE_STATUS_NOTAUTHED)
                {
                    $releasedTx['StatusDetail'] = 'The transaction has been declined.';
                }

                $query = array(
                    'requestBody' => SagepayUtil::arrayToQueryString($data),
                    'resultBody' => SagepayUtil::arrayToQueryString($response),
                    'status' => $response['Status'],
                    'command' => SAGEPAY_TXN_RELEASE
                );

                $this->redirect($this->integrationType, 'admin_result', $query);
            }
            else
            {
                $this->error = true;
            }
        }
        if (!empty($paymentTx))
        {
            $view = new HelperView('admin/release');
            $view->setData(array(
                'env' => $this->sagepayConfig->getEnv(),
                'vendorName' => $this->sagepayConfig->getVendorName(),
                'integrationType' => $this->integrationType,
                'result' => $paymentTx,
                'val' => array(
                    'ok' => !$this->error,
                    'errorStatusString' => $message
                ),
            ));
            $view->render();
        }
        else
        {
            $this->redirect($this->integrationType, 'admin');
        }
    }

    /**
     * Action abort the transaction
     */
    public function actionAbort()
    {
        // Check original VendorTxCode
        if (filter_input(INPUT_GET, 'origVtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_GET, 'origVtx'));

            $view = new HelperView('admin/abort');
            $view->setData(array(
                'env' => $this->sagepayConfig->getEnv(),
                'vendorName' => $this->sagepayConfig->getVendorName(),
                'integrationType' => $this->integrationType,
                'result' => $result,
            ));
            $view->render();
            return;
        }

        // Check if form was submitted
        if (filter_input(INPUT_POST, 'origVtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_POST, 'origVtx'));

            $data = array(
                'VPSProtocol' => $this->sagepayConfig->getProtocolVersion(),
                'TxType' => SAGEPAY_TXN_ABORT,
                'Vendor' => $this->sagepayConfig->getVendorName(),
                'VendorTxCode' => $result['vendorTxCode'],
                'VPSTxId' => $result['vpsTxId'],
                'SecurityKey' => $result['securityKey'],
                'TxAuthNo' => $result['txAuthNo']
            );

            $response = SagepayCommon::requestPost($this->sagepayConfig->getSharedUrl('abort'), $data);

            $result += $response;
            if ($response['Status'] == SAGEPAY_REMOTE_STATUS_OK)
            {
                $result['Status'] = SAGEPAY_REMOTE_STATUS_ABORTED;
                $result['StatusDetail'] = 'The transaction was successfully processed.';
                $result['CapturedAmount'] = $result['amount'];
                $payment->update(filter_input(INPUT_POST, 'origVtx'), $result);
            }


            $query = array(
                'requestBody' => SagepayUtil::arrayToQueryString($data),
                'resultBody' => SagepayUtil::arrayToQueryString($response),
                'status' => $response['Status'],
                'command' => SAGEPAY_TXN_ABORT
            );

            $this->redirect($this->integrationType, 'admin_result', $query);
        }
        $this->redirect($this->integrationType, 'admin');
    }

    /**
     * Action authorise the transaction
     */
    public function actionAuthorise()
    {
        $errorMessage = '';

        // Check if form was submitted
        if (filter_input(INPUT_POST, 'origVtx'))
        {
            $payment = new ModelPayment();
            $paymentTxOrig = $payment->getByVendorTxCode(filter_input(INPUT_POST, 'origVtx'));

            $data = array(
                'VPSProtocol' => $this->sagepayConfig->getProtocolVersion(),
                'TxType' => SAGEPAY_TXN_AUTHORISE,
                'Vendor' => $this->sagepayConfig->getVendorName(),
                'VendorTxCode' => filter_input(INPUT_POST, 'VendorTxCode'),
                'Amount' => filter_input(INPUT_POST, 'Amount'),
                'Description' => filter_input(INPUT_POST, 'Description'),
                'RelatedVPSTxID' => $paymentTxOrig['vpsTxId'],
                'RelatedVendorTxCode' => filter_input(INPUT_POST, 'origVtx'),
                'RelatedSecurityKey' => $paymentTxOrig['securityKey'],
                'ApplyAVSCV2' => filter_input(INPUT_POST, 'ApplyAvsCv2'),
            );

            $errorMessage = $this->validateAuthoriseAction($paymentTxOrig, $data);
            // Check if authorise was failed
            if (!$errorMessage)
            {
                $response = SagepayCommon::requestPost($this->sagepayConfig->getSharedUrl('authorise'), $data);

                if ($response['Status'] == SAGEPAY_REMOTE_STATUS_OK)
                {
                    $paymentTxOrig['CapturedAmount'] = $paymentTxOrig['capturedAmount'] + filter_input(INPUT_POST, 'Amount');
                    $paymentTxOrig['Status'] = SAGEPAY_REMOTE_STATUS_AUTHENTICATED;

                    $payment->update(filter_input(INPUT_POST, 'origVtx'), $paymentTxOrig);

                    $paymentTxOrig = $this->ucFirstFields($paymentTxOrig);
                    $paymentTx = array_merge($paymentTxOrig, $data, $response);
                    $paymentTx['StatusDetail'] = SAGEPAY_TXN_AUTHORISE . ' transaction taken through Order Admin area.';
                    $paymentTx['CapturedAmount'] = filter_input(INPUT_POST, 'Amount');

                    $payment->insert($paymentTx);
                }

                $query = array(
                    'requestBody' => SagepayUtil::arrayToQueryString($data),
                    'resultBody' => SagepayUtil::arrayToQueryString($response),
                    'status' => $response['Status'],
                    'command' => SAGEPAY_TXN_AUTHORISE
                );

                $this->redirect($this->integrationType, 'admin_result', $query);
            }
        }
        // Check original VendorTxCode
        else if (filter_input(INPUT_GET, 'origVtx'))
        {
            $payment = new ModelPayment();
            $paymentTxOrig = $payment->getByVendorTxCode(filter_input(INPUT_GET, 'origVtx'));
        }
        else
        {
            $this->redirect($this->integrationType, 'admin');
        }

        $view = new HelperView('admin/authorise');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'result' => $paymentTxOrig,
            'val' => array('ok' => true),
            'newVtx' => SagepayCommon::vendorTxCode(time(), SAGEPAY_TXN_AUTHORISE, $this->sagepayConfig->getVendorName()),
            'actionUrl' => url(array($this->integrationType, 'authorise')) . '?origVtx=' . filter_input(INPUT_GET, 'origVtx'),
            'error' => $errorMessage ? true : false,
            'message' => $errorMessage
        ));
        $view->render();
    }

    /**
     * Action remove all the payments
     */
    public function actionDeleteAllPayments()
    {
        $view = new HelperView('admin/deleted');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'numDeleted' => ModelAbstract::factory('Payment')->deleteAll(),
        ));
        $view->render();
    }

    /**
     * Action remove all the customers DIRECT mode
     */
    public function actionDeleteAllCustomers()
    {
        HelperCommon::clearStore('account');
        $sagepayToken = new SagepayToken($this->sagepayConfig);
        $cardTokens = ModelAbstract::factory('Card')->getAll();
        foreach ($cardTokens as $card)
        {
            $sagepayToken->remove($card->token);
        }
        $view = new HelperView('admin/deleted');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'numDeleted' => ModelAbstract::factory('Customer')->deleteAll()
        ));
        $view->render();
    }

    /**
     * Helper method that capitalize fields name
     *
     * @param array $fields
     *
     * @return array
     */
    public function ucFirstFields(array $fields)
    {
        $newFields = array();
        foreach ($fields as $fields => $value)
        {
            if ($value !== null)
            {
                $ucField = ucfirst($fields);
                $newFields[$ucField] = $value;
            }
        }
        return $newFields;
    }


    /**
     * Validation for action authorise the transaction
     *
     * @param array $oldData
     * @param array $newData
     *
     * @return string  Returns a String with all messages.
     */
    public function validateAuthoriseAction($oldData, $newData)
    {
        $errors = $this->validate($this->_rules, $newData);
        $message = '';
        // Check if authorise was failed
        if ($errors)
        {
            $hMessage = new HelperMessage();
            $message = $hMessage->getAllMessages($errors, array(
                'VendorTxCode' => 'Authorise VendorTxCode',
                'Description' => 'Authorise Description',
                'Amount' => 'Authorise Amount',
                'ApplyAVSCV2' => 'ApplyAVSCV2'
            ));
        }

        if ($oldData['cardType'] == 'MAESTRO')
        {
            $maxAmount = $oldData['amount'];
        }
        else
        {
            $maxAmount = $oldData['amount'] * 1.15;
        }

        if ($oldData['capturedAmount'] + $newData['Amount'] > $maxAmount)
        {
            $currentMaxAmount = $maxAmount - $oldData['capturedAmount'];
            $message = $message ? $message . ', ' : '';
            $message .= 'Authorise Amount is out of range, must be less than ' . number_format($currentMaxAmount, 2) . '.';
        }

        return $message;
    }

}
