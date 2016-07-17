<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Abstract Controller for SERVER & DIRECT integration method
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
abstract class ControllerServerDirect extends ControllerAdmin
{
    /**
     * Action welcome page
     */
    public function actionWelcome()
    {
        $siteFqdn = $this->sagepayConfig->getSiteFqdn();

        // render welcome tpl
        $view = new HelperView('server-and-direct/welcome');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'siteFqdn' => $siteFqdn,
            'fullUrl' => url(array($this->integrationType), $siteFqdn),
            'protocolVersion' => $this->sagepayConfig->getProtocolVersion(),
            'txType' => $this->sagepayConfig->getTxType(),
            'currency' => $this->sagepayConfig->getCurrency(),
            'pdo' => $this->dbHelper->getPdo(),
            'db' => $this->dbHelper->getDatabase(),
            'username' => $this->dbHelper->getUsername(),
            'purchaseUrl' => $this->purchaseUrl,
        ));
        $view->render();
    }

    /**
     * Action entry Login/Register page
     */
    public function actionEntry()
    {
        $message = '';
        // Check if was logged
        if (HelperCommon::getStore('account'))
        {
            $this->redirect($this->integrationType, 'basket');
        }
        // Check if form was submitted
        if (count(filter_input_array(INPUT_POST)))
        {
            HelperCommon::clearStore('account');
            $rules = array(
                'email' => array(
                    array('notEmpty'),
                    array('maxLength', array(255)),
                    array('email')
                ),
                'password' => array(
                    array('notEmpty'),
                    array('maxLength', array(255)),
                ),
            );
            $data = array(
                'email' => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
                'password' => filter_input(INPUT_POST, 'password')
            );
            $errors = $this->validate($rules, $data);

            $hMessage = new HelperMessage();
            $message = $hMessage->getAllMessages($errors, array('email' => 'Email', 'password' => 'Password'));

            // Check if login was failed
            if (!$errors)
            {
                $password = md5($this->sagepayConfig->getCustomerPasswordSalt() . filter_input(INPUT_POST, 'password'));
                $customerId = $this->checkCustomer(filter_input(INPUT_POST, 'email'), $password);
                if (!$customerId !== 0)
                {
                    HelperCommon::setStore('account',
                            array('email' => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL), 'password' => $password,
                        'id' => $customerId));
                    $this->redirect($this->integrationType, 'basket');
                }
                else
                {
                    $this->error = true;
                    $message = 'Login failed';
                }
            }
            else
            {
                $this->error = true;
                $message = "Sorry, the following problems were found: " . $message;
            }
        }

        $current = array('email' => '', 'password' => '');
        if (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))
        {
            $current['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        }

        // render entry tpl
        $view = new HelperView('server-and-direct/entry');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => false,
            'controller' => $this->integrationType,
            'current' => $current,
            'error' => $this->error,
            'message' => $message,
        ));
        $view->render();
    }

    /**
     * Action confirm page
     */
    public function actionConfirm()
    {
        parent::actionConfirm();

        $perceedUrlParts = array($this->integrationType, 'register');
        $backUrlParts = array($this->integrationType, 'details');
        $error = false;
        $message = '';

        if ($this->integrationType == SAGEPAY_DIRECT)
        {
            $card = HelperCommon::getStore('card');
            $backUrlParts[1] = 'card';
            if (empty($card['cardType']))
            {
                $backUrlParts[1] = 'card-token';
            }
        }

        $api = $this->buildApi();

        // Get products form basket
        $items = array();
        $basketItems = $api->getBasket()->getItems();
        foreach ($basketItems as $item)
        {
            $items[] = array(
                'urlImage' => $this->getProductUrlImage($item->getDescription()),
                'description' => $item->getDescription(),
                'quantity' => $item->getQuantity(),
                'unitGrossAmount' => number_format($item->getUnitGrossAmount(), 2),
                'totalGrossAmount' => number_format($item->getTotalGrossAmount(), 2),
            );
        }

        if (filter_input(INPUT_GET, 'error'))
        {
            $error = true;
            $message = base64_decode(filter_input(INPUT_GET, 'error'));
        }

        // Render confirm tpl
        $view = new HelperView('server-and-direct/confirm');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'details' => $this->data['details'],
            'deliveryGrossPrice' => $this->data['deliveryGrossPrice'],
            'totalGrossPrice' => $this->data['totalGrossPrice'],
            'purchaseUrl' => $this->purchaseUrl,
            'currency' => $this->sagepayConfig->getCurrency(),
            'card' => HelperCommon::getStore('card'),
            'basket' => array(
                'items' => $items
            ),
            'perceedUrl' => url($perceedUrlParts),
            'backUrl' => url($backUrlParts),
            'message' => $message,
            'error' => $error,
        ));
        $view->render();
    }

    /**
     * Action logout of system page
     */
    public function actionLogout()
    {
        HelperCommon::clearStore('account');

        // Render logout tpl
        $view = new HelperView('server-and-direct/logout');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
        ));
        $view->render();
    }

    /**
     * Action result admin transaction
     */
    public function actionAdminResult()
    {
        $view = new HelperView('admin/result');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'command' => filter_input(INPUT_GET, 'command'),
            'status' => filter_input(INPUT_GET, 'status'),
            'requestBody' => filter_input(INPUT_GET, 'requestBody'),
            'resultBody' => filter_input(INPUT_GET, 'resultBody')
        ));
        $view->render();
    }

    /**
     * Check customer in system
     *
     * @param string $email
     * @param string $password
     * @return boolean|int
     */
    protected function checkCustomer($email, $password)
    {
        $response = 0;
        $sql = 'SELECT * FROM customer WHERE email = ?';
        $stm = $this->dbHelper->execute($sql, array($email));
        // Check mysql result
        if ($stm)
        {
            if ($stm->rowCount())
            {
                $row = $stm->fetch(PDO::FETCH_ASSOC);
                if ($row['hashedPassword'] == $password)
                {
                    $response = intval($row['id']);
                }
            }
            else
            {
                // Insert new customer
                $customer = new ModelCustomer($this->dbHelper);
                $customer->insert(array('email' => $email, 'hashedPassword' => $password));
                $response = intval($customer->lastInsertId());
            }
        }
        return $response;
    }

}
