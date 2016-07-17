<?php defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Abstract Controller for Integration methods
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
abstract class ControllerAbstract
{

    /**
     * Integration method FORM, SERVER or DIRECT
     *
     * @var string
     */
    protected $integrationType = '';

    /**
     * Collections of data
     *
     * @var array
     */
    protected $data = array();

    /**
     * Error for validation
     *
     * @var string[]
     */
    protected $error = array();

    /**
     * Define URL for integration methods
     *
     * @var string
     */
    protected $purchaseUrl = '';

    /**
     * Define SagepaySettings
     *
     * @var SagepaySettings
     */
    protected $sagepayConfig;

    /**
     * Define HelperDatabase
     *
     * @var HelperDatabase
     */
    protected $dbHelper;

    /**
     * Throw exception if accessed wrong path
     *
     * @param string $name
     * @param mixed $arguments
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        SagepayUtil::log('Called invalid action "'.$name .'" with params: '.json_encode($arguments));
        throw new Exception('Invalid page was accessed');
    }

    /**
     * Hook that is trigged before actions
     *
     * @return boolean
     */
    public function before()
    {
        return true;
    }

    /**
     * Add new model to view
     *
     * @param string $key
     * @param mix $value
     */
    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Set SagepaySettings for controller
     *
     * @param SagepaySettings $sagepayConfig
     */
    public function setSagepayConfig(SagepaySettings $sagepayConfig)
    {
        $this->sagepayConfig = $sagepayConfig;
    }

    /**
     * Set HelperDatabase for controller
     *
     * @param HelperDatabase $dbHelper
     */
    public function setDbHelper(HelperDatabase $dbHelper)
    {
        $this->dbHelper = $dbHelper;
    }

    /**
     * Redirect to location
     *
     * @param string $controller
     * @param string $action
     * @param array $query
     */
    protected function redirect($controller, $action = 'index', $query = array())
    {
        $queryStr = '';
        // Check if query is not empty
        if (count($query) > 0)
        {
            $queryStr = '?' . SagepayUtil::arrayToQueryString($query, '&', true);
        }

        if ($controller == 'index')
        {
            $args = array('');
        }
        else
        {
            $args = array($controller, $action);
        }

        header('Location: ' . url($args) . $queryStr);
        exit;
    }

    /**
     * Action to view basket page
     */
    public function actionBasket()
    {
        $this->checkAccount();
        $message = '';
        $selectedProducts = array();

        // Check if form was submitted
        if (count(filter_input_array(INPUT_POST)))
        {
            $selectedProducts = array();
            // Fill selected product from request
            foreach (array_keys(filter_input_array(INPUT_POST)) as $key)
            {
                $matches = array();
                if (preg_match('/^quantity([0-9]*)$/', $key, $matches) && isset($matches[1]))
                {
                    $selectedProducts[$matches[1]] = filter_input(INPUT_POST, $key, FILTER_VALIDATE_FLOAT);
                }
            }
            HelperCommon::clearStore('products');

            // Check if was select at least 1 item
            if ($this->checkProducts($selectedProducts))
            {
                HelperCommon::setStore('products', $selectedProducts);
                $this->redirect($this->integrationType,
                        $this->integrationType == SAGEPAY_DIRECT ? 'basket_checkout' : 'details');
            }
            else
            {
                $this->error = true;
                $message = 'You did not select any items to buy. Please select at least 1 item.';
            }
        }

        $productsRows = ModelAbstract::factory('Product')->getAll();

        // Create list of products for view
        $products = array();
        foreach ($productsRows as $row)
        {
            $products[] = array(
                'id' => $row->id,
                'title' => $row->title,
                'price' => $row->price,
                'tax' => $row->tax,
                'image' => $row->image,
            );
        }

        // Render view basket
        $view = new HelperView('common/basket');
        $view->setData(array(
            'actionUrl' => url(array($this->integrationType, 'basket')),
            'backUrl' => $this->integrationType == SAGEPAY_FORM ? url(array('form')) : url(array($this->integrationType,
                        'welcome')),
            'message' => $message,
            'error' => $this->error,
            'products' => $products,
            'selectedProducts' => $selectedProducts,
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'currency' => $this->sagepayConfig->getCurrency(),
            'integrationType' => $this->integrationType,
        ));
        $view->render();
    }

    /**
     * Action for action details page
     */
    public function actionDetails()
    {
        $this->checkProducts(null, true);
        $deliveryErrors = array();
        $isDeliverySame = true;
        $message = '';
        $token = null;

        $details = HelperCommon::getStore('details');
        if (empty($details))
        {
            $details = array();
        }
        // Check if form was submitted
        if (count(filter_input_array(INPUT_POST)))
        {
            HelperCommon::clearStore('details');
            HelperCommon::clearStore('token');
            // save current token
            if (!is_null(filter_input(INPUT_POST, 'token')))
            {
                $account = HelperCommon::getStore('account');
                // Save token to account only for DIRECT
                if ($account)
                {
                    $token = filter_input(INPUT_POST, 'token');
                    $account['token'] = $token;
                    if (empty($token))
                    {
                        // Clear token
                        unset($account['token']);
                    }
                    HelperCommon::setStore('account', $account);
                }
            }

            $billingDetails = $this->createCustomerDetails(filter_input_array(INPUT_POST), 'billing');
            $details = array_merge($details, $this->customerDetailsToArray($billingDetails, 'billing'));
            $billingErrors = $billingDetails->validate();

            /* Get error messages */
            $helperMessage = new HelperMessage();
            $message = $helperMessage->getAllMessages($billingErrors, $this->getDefaultCustomerErros('Billing'));

            $isDeliverySame = filter_input(INPUT_POST, 'isDeliverySame') == "YES";
            HelperCommon::setStore('isDeliverySame', $isDeliverySame);
            // Check if delivery was selected
            if (!$isDeliverySame)
            {
                $deliveryDetails = $this->createCustomerDetails(filter_input_array(INPUT_POST), 'delivery');
                $details = array_merge($details, $this->customerDetailsToArray($deliveryDetails, 'delivery'));
                $deliveryErrors = $deliveryDetails->validate();
                $message .= ($message ? ', ' : '') . $helperMessage->getAllMessages($deliveryErrors,
                                $this->getDefaultCustomerErros('Delivery'));
            }

            // Check if billing or delivery data was failed
            if (empty($billingErrors) && empty($deliveryErrors))
            {
                // Save billing details
                $this->saveCustomerDetails($billingDetails, 'billing', 'details');

                // Check delivery details
                if (!$isDeliverySame)
                {
                    // Save delivery details
                    $this->saveCustomerDetails($deliveryDetails, 'delivery', 'details');
                }

                // Redirect user
                if ($this->sagepayConfig->basketAsXmlDisabled() && !$this->sagepayConfig->getCollectRecipientDetails())
                {
                    if ($this->integrationType == SAGEPAY_DIRECT)
                    {
                        $this->redirect($this->integrationType, (!empty($token) ? 'card-token' : 'card'));
                    }
                    else
                    {
                        $this->redirect($this->integrationType, 'confirm');
                    }
                }
                else
                {
                    $this->redirect($this->integrationType, 'extra-information');
                }
            }

            $message = 'Sorry, the following problems were found: ' . $message;
            $this->error = true;
        }

        // Set default values
        $current = $this->getDefaultDetails();
        if (!empty($details))
        {
            $current = array_merge($current, $details);
        }

        // Get all tokens for current logged user
        $account = HelperCommon::getStore('account');
        $tokens = array();
        if ($this->integrationType == SAGEPAY_DIRECT && isset($account['id']))
        {
            $card = new ModelCard();
            $tokens = $card->getAllTokensByCustomerId($account['id']);
        }

        // Render view details
        $view = new HelperView('common/details');
        $view->setData(array(
            'actionUrl' => url(array($this->integrationType, 'details')),
            'backUrl' => url(array($this->integrationType, 'basket')),
            'message' => $message,
            'error' => $this->error,
            'current' => $current,
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'allTokens' => $tokens,
            'token' => HelperCommon::getStore('account', 'token'),
            'isDeliverySame' => $isDeliverySame,
        ));
        $view->render();
    }

    /**
     * Action for action extra information page
     */
    public function actionExtraInformation()
    {
        // Check basket xml and collect recipient details
        if ($this->sagepayConfig->basketAsXmlDisabled() && !$this->sagepayConfig->getCollectRecipientDetails())
        {
            $this->redirect($this->integrationType, 'details');
        }

        if ($this->integrationType != SAGEPAY_DIRECT)
        {
            $message = $this->saveExtra($this->integrationType, 'confirm');
        }
        else
        {
            // Check token for DIRECT integration type
            $token = HelperCommon::getStore('account', 'token');
            $message = $this->saveExtra($this->integrationType, ($token ? 'card-token' : 'card'));
        }
        $current = $this->getDefaultExtraDetails();
        // Check if extra information was failed
        if ($this->error)
        {
            $current['extra'] = filter_input(INPUT_POST, 'extra');
            $current['tourOperatorFrom'] = filter_input(INPUT_POST, 'tourFrom');
            $current['tourOperatorTo'] = filter_input(INPUT_POST, 'tourTo');
            $current['carRentalFrom'] = filter_input(INPUT_POST, 'carFrom');
            $current['carRentalTo'] = filter_input(INPUT_POST, 'carTo');
            $current['cruiseFrom'] = filter_input(INPUT_POST, 'cruiseFrom');
            $current['cruiseTo'] = filter_input(INPUT_POST, 'cruiseTo');
            $current['hotelFrom'] = filter_input(INPUT_POST, 'hotelFrom');
            $current['hotelTo'] = filter_input(INPUT_POST, 'hotelTo');
            $current['numberInParty'] = filter_input(INPUT_POST, 'numberInParty');
            $current['guestName'] = filter_input(INPUT_POST, 'guestName');
            $current['referenceNumber'] = filter_input(INPUT_POST, 'referenceNumber');
            $current['roomRate'] = filter_input(INPUT_POST, 'roomRate');
        }

        // Array of trip options
        $tripSelectors = array(
            'tour' => 'Tour',
            'car' => 'Car Rental',
            'cruise' => 'Cruise',
            'hotel' => 'Hotel'
        );

        // Render view extra information
        $view = new HelperView('common/extra_information');
        $view->setData(array(
            'actionUrl' => url(array($this->integrationType, 'extra-information')),
            'backUrl' => url(array($this->integrationType, 'details')),
            'current' => $current,
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'collectRecipientDetails' => $this->sagepayConfig->getCollectRecipientDetails(),
            'basketXml' => !$this->sagepayConfig->basketAsXmlDisabled(),
            'tripSelectors' => $tripSelectors,
            'error' => $this->error,
            'message' => $message,
        ));
        $view->render();
    }

    /**
     * Action confirm transaction page
     */
    public function actionConfirm()
    {
        $this->checkDetails();
    }

    /**
     * Build Api for transaction
     *
     * @return SagepayAbstractApi
     */
    protected function buildApi()
    {
        $details = HelperCommon::getStore('details');
        if (HelperCommon::getStore('isDeliverySame'))
        {
            // set default delivery
            $fields = array('Firstnames', 'Surname', 'Address1', 'Address2', 'City', 'PostCode', 'Country', 'State', 'Phone');
            $details = $this->setDefaultDelivery($details, $fields);
            HelperCommon::setStore('details', $details);
        }
        $basket = $this->getBasketFromProducts();

        $basket->setDeliveryNetAmount(1.5);
        $basket->setDeliveryTaxAmount(0.05);
        $basket->setDescription('DVDs from Sagepay Demo Page');

        $extra = HelperCommon::getStore('extra');
        $api = SagepayApiFactory::create($this->integrationType, $this->sagepayConfig);

        if (is_null($api))
        {
            $this->redirect('index');
        }

        // Save extra information to API
        if (is_array($extra))
        {
            foreach ($extra as $key => $value)
            {
                $call = 'set' . ucfirst($key);
                if (method_exists($basket, $call))
                {
                    $basket->$call($value);
                }
                else if (!empty($value))
                {
                    // Save Recipient Details
                    $api->updateData(array(ucfirst($key) => $value));
                }
            }
        }
        $api->setBasket($basket);

        // Add billing and delivery details
        $address1 = $this->createCustomerDetails($details, 'billing');
        $address2 = $this->createCustomerDetails($details, 'delivery');
        $api->addAddress($address1);
        $api->addAddress($address2);

        $account = HelperCommon::getStore('account');
        if ($account)
        {
            $customer = new SagepayCustomer();
            $customer->setCustomerId($account['id']);
            $api->setCustomer($customer);
        }
        $this->addData('api', $api);
        $this->addData('env', $this->sagepayConfig->getEnv());
        $this->addData('deliveryGrossPrice',
                number_format($api->getBasket()->getDeliveryGrossAmount(), 2) . ' ' . $this->sagepayConfig->getCurrency());
        $this->addData('totalGrossPrice',
                number_format($api->getBasket()->getAmount(), 2) . ' ' . $this->sagepayConfig->getCurrency());
        $details['BillingAddress2'] = $details['BillingAddress2'] ? $details['BillingAddress2'] . '<br>' : '';
        $details['BillingState'] = $details['BillingState'] ? $details['BillingState'] . '<br>' : '';
        $details['BillingPostCode'] = $details['BillingPostCode'] ? $details['BillingPostCode'] . '<br>' : '';
        $details['DeliveryAddress2'] = $details['DeliveryAddress2'] ? $details['DeliveryAddress2'] . '<br>' : '';
        $details['DeliveryState'] = $details['DeliveryState'] ? $details['DeliveryState'] . '<br>' : '';
        $details['DeliveryPostCode'] = $details['DeliveryPostCode'] ? $details['DeliveryPostCode'] . '<br>' : '';
        $this->addData('details', $details);
        return $api;
    }

    /**
     * Helper for error
     *
     * @param string $errorMessage
     * @param string $backUrl
     */
    protected function helperError($errorMessage, $backUrl)
    {
        $view = new HelperView('common/error');
        $view->setData(array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'backUrl' => $backUrl,
            'errorMessage' => $errorMessage,
            'exception' => isset($this->data['exception']) ? $this->data['exception'] : false,
        ));
        $view->render();
        return;
    }

    /**
     * Get basket from products
     *
     * @return SagepayBasket
     */
    protected function getBasketFromProducts()
    {
        $basket = false;
        $products = $this->dbHelper->execute('SELECT * from product');
        // Create basket from saved products
        foreach ($products as $row)
        {
            if (HelperCommon::getStore('products', $row['id']) > 0)
            {
                if ($basket === false)
                {
                    $basket = new SagepayBasket();
                }
                $item = new SagepayItem();
                $item->setDescription($row['title']);
                $item->setProductCode($row['code']);
                $item->setProductSku($row['sku']);
                $item->setUnitTaxAmount($row['tax']);
                $item->setQuantity(HelperCommon::getStore('products', $row['id']));
                $item->setUnitNetAmount($row['price']);
                $basket->addItem($item);
            }
        }
        return $basket;
    }

    /**
     * Check saved products
     *
     * @param array $products
     *
     * @param boolean $redirect
     *
     * @return boolean
     */
    protected function checkProducts($products = null, $redirect = false)
    {
        if ($products === null)
        {
            // restored product from session
            $products = HelperCommon::getStore('products');
        }
        $qty = 0;
        // Check qty products
        if (!empty($products))
        {
            foreach ($products as $q)
            {
                $qty += $q;
            }
        }
        $valide = ($qty > 0);
        if ($redirect)
        {
            if (!$valide)
            {
                $this->redirect($this->integrationType);
            }
            else
            {
                $this->checkAccount();
            }
        }
        return $valide;
    }

    /**
     * Define default billing and dlivery details
     *
     * @return string[]
     */
    protected function getDefaultDetails()
    {
        return array(
            'BillingFirstnames' => 'Fname Mname',
            'BillingSurname' => 'Surname',
            'BillingAddress1' => 'BillAddress Line 1',
            'BillingAddress2' => 'BillAddress Line 2',
            'BillingCity' => 'BillCity',
            'BillingPostCode' => 'W1A 1BL',
            'BillingCountry' => 'GB',
            'BillingState' => '',
            'BillingPhone' => '44 (0)7933 000 000',
            'customerEmail' => 'customer@example.com',
            'dateOfBirth' => '',
            // delivery details
            'DeliveryFirstnames' => '',
            'DeliverySurname' => '',
            'DeliveryAddress1' => '',
            'DeliveryAddress2' => '',
            'DeliveryCity' => '',
            'DeliveryPostCode' => '',
            'DeliveryCountry' => '',
            'DeliveryState' => '',
            'DeliveryPhone' => '',
        );
    }

    /**
     * Define default extra details
     *
     * @return string[]
     */
    protected function getDefaultExtraDetails()
    {
        return array(
            'extra' => '',
            'hotelFrom' => '',
            'hotelTo' => '',
            'tourOperatorFrom' => '',
            'tourOperatorTo' => '',
            'carRentalFrom' => '',
            'carRentalTo' => '',
            'cruiseFrom' => '',
            'cruiseTo' => '',
            'numberInParty' => '',
            'guestName' => '',
            'referenceNumber' => '',
            'roomRate' => '',
            'fiRecipientAcctNumber' => '',
            'fiRecipientDob' => '',
            'fiRecipientPostCode' => '',
            'fiRecipientSurname' => '',
        );
    }

    /**
     * Define default customer keys
     *
     * @param string $type
     * @return string[]
     */
    protected function getDefaultCustomerKeys($type)
    {
        $result = array();
        $keys = array(
            'Firstnames' => 'firstname',
            'Surname' => 'lastname',
            'Address1' => 'address1',
            'Address2' => 'address2',
            'City' => 'city',
            'PostCode' => 'postcode',
            'Country' => 'country',
            'State' => 'state',
            'Phone' => 'phone'
        );

        foreach ($keys as $key => $value)
        {
            $result[$type . $key] = $value;
        }
        return $result;
    }

    /**
     * Define default customer errors
     *
     * @param string $type
     * @return string[]
     */
    protected function getDefaultCustomerErros($type)
    {
        return array(
            'firstname' => $type . ' First Name(s)',
            'lastname' => $type . ' Surname',
            'address1' => $type . ' Address Line 1',
            'address2' => $type . ' Address Line 2',
            'city' => $type . ' City',
            'postcode' => $type . ' Post Code',
            'country' => $type . ' Country',
            'state' => $type . ' State Code (U.S. only)',
            'email' => 'Email Address',
        );
    }

    /**
     * Save extra data to session
     *
     * @param string $redirectQ
     *
     * @param string $redirectP
     *
     * @return string
     */
    protected function saveExtra($redirectQ, $redirectP)
    {
        $message = '';

        if (count(filter_input_array(INPUT_POST)))
        {
            $type = null;
            $extra = array();
            $extraTypes = array(
                'cruise' => 'cruise',
                'hotel' => 'hotel',
                'tour' => 'tourOperator',
                'car' => 'carRental'
            );
            $extraName = filter_input(INPUT_POST, 'extra');

            // Check extra type
            if (isset($extraTypes[$extraName]))
            {
                $type = $extraTypes[$extraName];
            }

            $errors = false;
            if (!empty($type))
            {

                $extraFrom = filter_input(INPUT_POST, $extraName . 'From');
                $extraTo = filter_input(INPUT_POST, $extraName . 'To');
                $extra[$type] = array();
                $extra[$type]['checkIn'] = $extraFrom && strtotime($extraFrom) ? date("Y-m-d", strtotime($extraFrom)) : $extraFrom;
                $extra[$type]['checkOut'] = $extraTo && strtotime($extraTo) ? date("Y-m-d", strtotime($extraTo)) : $extraTo;

                if ($type === 'hotel')
                {
                    $extra[$type]['numberInParty'] = filter_input(INPUT_POST, 'numberInParty');
                    $extra[$type]['guestName'] = filter_input(INPUT_POST, 'guestName');
                    $extra[$type]['folioRefNumber'] = filter_input(INPUT_POST, 'referenceNumber');
                    $extra[$type]['confirmedReservation'] = !!filter_input(INPUT_POST, 'confirmedReservation') ? 'Y' : 'N';
                    $extra[$type]['dailyRoomRate'] = filter_input(INPUT_POST, 'roomRate');
                }
                $rules = $this->extraInformationRules($type);
                $errors = $this->validate($rules, $extra[$type]);
            }

            // Check collect recipient details
            if ($this->sagepayConfig->getCollectRecipientDetails())
            {
                $extra['fiRecipientAcctNumber'] = filter_input(INPUT_POST, 'fiRecipientAcctNumber');
                $extra['fiRecipientDob'] = filter_input(INPUT_POST, 'fiRecipientDob');
                $extra['fiRecipientPostCode'] = filter_input(INPUT_POST, 'fiRecipientPostCode');
                $extra['fiRecipientSurname'] = filter_input(INPUT_POST, 'fiRecipientSurname');

                $fiRecipientRules = $this->recipientDetailsRules();
                $fiRecipientErrors = $this->validate($fiRecipientRules, $extra);
                if ($fiRecipientErrors)
                {
                    $errors = $errors ? $errors + $fiRecipientErrors : $fiRecipientErrors;
                }
            }

            // Check if extra information was failed
            if (!$errors)
            {
                HelperCommon::setStore('extra', $extra);
                $this->redirect($redirectQ, $redirectP);
            }
            else
            {
                $hMessage = new HelperMessage();
                $message = $hMessage->getAllMessages($errors,
                        array(
                    'checkIn' => 'Check In',
                    'checkOut' => 'Check Out',
                    'numberInParty' => 'Number In Party',
                    'guestName' => 'Guest Name',
                    'folioRefNumber' => 'Reference Number',
                    'dailyRoomRate' => 'Room Rate',
                    'fiRecipientAcctNumber' => 'Account number',
                    'fiRecipientDob' => 'Date of birth',
                    'fiRecipientPostCode' => 'Post code',
                    'fiRecipientSurname' => 'Surname'
                ));
                $this->error = true;
                $message = "Sorry, the following problems were found: " . $message;
            }
        }
        return $message;
    }

    /**
     * Define extra information rules
     *
     * @param string $type
     *
     * @return array
     */
    protected function extraInformationRules($type)
    {
        if (!$type)
        {
            return array();
        }
        // Define rules for all types
        $rules = array(
            'checkIn' => array(
                array('notEmpty'),
                array('regex', array("/^(19|20)\d{2}\-(0[1-9]|1[0-2])\-(0[1-9]|1\d|2\d|3[01])$/")),
            ),
            'checkOut' => array(
                array('notEmpty'),
                array('regex', array('/^(19|20)\d{2}\-(0[1-9]|1[0-2])\-(0[1-9]|1\d|2\d|3[01])$/')),
            )
        );

        // Add hotel specific rules
        if ($type == 'hotel')
        {
            $rules = array_merge($rules,
                    array(
                'numberInParty' => array(
                    array('notEmpty'),
                    array('maxLength', array(3)),
                    array('regex', array("/^(?!0)[0-9]+$/"))
                ),
                'guestName' => array(
                    array('notEmpty'),
                    array('regex', array("/^[a-zA-Z0-9_&.,'()+-\\\\\/\s]*$/")),
                ),
                'folioRefNumber' => array(
                    array('notEmpty')
                ),
                'dailyRoomRate' => array(
                    array('notEmpty'),
                    array('maxLength', array(10)),
                    array('regex', array("/^[0-9]*$/"))
                ),
            ));
        }
        return $rules;
    }

    /**
     * Define Recipient details rules
     *
     * @return array
     */
    protected function recipientDetailsRules()
    {
        return array(
            'fiRecipientAcctNumber' => array(
                array('maxLength', array(10)),
                array('regex', array("/^[a-zA-Z0-9]*$/")),
            ),
            'fiRecipientDob' => array(
                array('regex', array("/^((19|20)\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01]))?$/")),
            ),
            'fiRecipientPostCode' => array(
                array('maxLength', array(10)),
                array('regex', array("/^[a-zA-Z0-9\s-]*$/")),
            ),
            'fiRecipientSurname' => array(
                array('maxLength', array(20)),
                array('regex', array("/^[a-zA-Z\']*$/")),
            ),
        );
    }

    /**
     * Set default delivery from billing details
     *
     * @param array $data
     * @param array $keys
     *
     * @return array
     */
    protected function setDefaultDelivery($data, $keys)
    {
        foreach ($keys as $key)
        {
            if (isset($data['Billing' . $key]))
            {
                $data['Delivery' . $key] = $data['Billing' . $key];
            }
        }
        return $data;
    }

    /**
     * Get url form product images
     *
     * @param string $title
     * @param boolean $isSmall
     * @return string
     */
    protected function getProductUrlImage($title, $isSmall = false)
    {
        $sql = 'SELECT * FROM product WHERE title = ?';
        $stm = $this->dbHelper->execute($sql, array($title));
        $image = '';
        if ($stm)
        {
            $result = $stm->fetch();
            $image = $result['image'];
        }
        return BASE_PATH . $image . ($isSmall ? '-small' : '') . '.gif';
    }

    /**
     * Create and populate customer details
     *
     * @param array $data
     * @param array $type
     * @return SagepayCustomerDetails
     */
    protected function createCustomerDetails($data, $type)
    {
        $customerdetails = new SagepayCustomerDetails();
        $keys = $this->getDefaultCustomerKeys($type);
        foreach ($keys as $key => $value)
        {
            if (isset($data[$key]))
            {
                $customerdetails->$value = $data[$key];
            }
            if (isset($data[ucfirst($key)]))
            {
                $customerdetails->$value = $data[ucfirst($key)];
            }
        }
        if ($type == 'billing' && isset($data['customerEmail']))
        {
            $customerdetails->email = $data['customerEmail'];
        }
        return $customerdetails;
    }

    /**
     * Export customer details to array
     *
     * @param SagepayCustomerDetails $customerDetails
     * @param string $type
     * @return string[]
     */
    protected function customerDetailsToArray(SagepayCustomerDetails $customerDetails, $type)
    {
        $keys = $this->getDefaultCustomerKeys($type);
        $details = array();
        foreach ($keys as $key => $value)
        {
            $details[ucfirst($key)] = $customerDetails->$value;
        }
        if ($type == 'billing')
        {
            $details['customerEmail'] = $customerDetails->email;
        }
        return $details;
    }

    /**
     * Save customer details to session
     *
     * @param SagepayCustomerDetails $customerDetails
     * @param string $type
     * @param string $storeKey
     */
    protected function saveCustomerDetails(SagepayCustomerDetails $customerDetails, $type, $storeKey)
    {
        $rawdetails = HelperCommon::getStore($storeKey) ? HelperCommon::getStore($storeKey) : array();
        $details = array_merge($rawdetails, $this->customerDetailsToArray($customerDetails, $type));
        HelperCommon::setStore($storeKey, $details);
    }

    /**
     * Validation data
     *
     * @param array $rules
     * @param array $data
     * @return string[]
     */
    protected function validate(array $rules, array $data)
    {
        $errors = array();
        foreach ($rules as $key => $rule)
        {
            if (isset($data[$key]))
            {
                $propertyValue = $data[$key];
                $validator = new SagepayValidator($propertyValue, $rule);
                if (!$validator->isValid())
                {
                    $errors[$key] = $validator->getErrors();
                }
            }
        }
        return $errors;
    }

    /**
     * Get payment data for SERVER and DIRECT
     *
     * @param boolean $statusOk
     *
     * @return array
     */
    protected function getPaymentResultData($statusOk = false)
    {
        $result = array();
        if (filter_input(INPUT_GET, 'vtx'))
        {
            $payment = new ModelPayment();
            $result = $payment->getByVendorTxCode(filter_input(INPUT_GET, 'vtx'));
        }
        if (empty($result))
        {
            $this->helperError('Transaction code is invalid: this can happen if you try to pay for multiple baskets at the same time. '
                    . 'Please contact [your customer service details] to check the status of your order.',
                    url(array('server')));
        }

        $items = array();
        $basket = $this->getBasketFromProducts();
        if ($basket)
        {
            foreach ($basket->getItems() as $item)
            {
                $items[] = array(
                    'quantity' => $item->getQuantity(),
                    'urlImage' => $this->getProductUrlImage($item->getDescription()),
                    'description' => $item->getDescription(),
                );
            }
        }
        $errorMessage = '';
        if (!$statusOk)
        {
            switch ($result['status'])
            {
                case 'REJECTED':
                    $errorMessage = 'Your order did not meet our minimum fraud screening requirements.';
                    break;
                case 'ABORT':
                    $errorMessage = 'You chose to Cancel your order on the payment pages.';
                    break;
                default:
                    $errorMessage = 'ERROR.';
            }
        }
        return array(
            'env' => $this->sagepayConfig->getEnv(),
            'vendorName' => $this->sagepayConfig->getVendorName(),
            'integrationType' => $this->integrationType,
            'basket' => array(
                'items' => $items
            ),
            'ord' => $result,
            'stOk' => $statusOk,
            'errorMessage' => $errorMessage,
        );
    }

    /**
     * Check account in database
     */
    protected function checkAccount()
    {
        // Check only for SERVER and DIRECT transaction
        if (in_array($this->integrationType, array(SAGEPAY_SERVER, SAGEPAY_DIRECT)))
        {
            $account = HelperCommon::getStore('account');
            if ($account)
            {
                $sql = 'SELECT id FROM customer WHERE id = ? AND email = ? AND hashedPassword = ?';
                $stm = $this->dbHelper->execute($sql, array($account['id'], $account['email'], $account['password']));
                $row = $stm->fetch(PDO::FETCH_ASSOC);
                if (empty($row) || empty($row['id']))
                {
                    HelperCommon::clearStore('account');
                    $this->redirect($this->integrationType);
                }
            }
        }
    }

    /**
     * Check details data
     */
    protected function checkDetails()
    {
        $details = HelperCommon::getStore('details');

        $billingDetails = $this->createCustomerDetails($details, 'billing');
        $details = array_merge($details, $this->customerDetailsToArray($billingDetails, 'billing'));
        $billingErrors = $billingDetails->validate();

        if (HelperCommon::getStore('isDeliverySame'))
        {
            $deliveryErrors = array();
        }
        else
        {
            $deliveryDetails = $this->createCustomerDetails($details, 'delivery');
            $details = array_merge($details, $this->customerDetailsToArray($deliveryDetails, 'delivery'));
            $deliveryErrors = $deliveryDetails->validate();
        }

        if (!empty($billingErrors) || !empty($deliveryErrors))
        {
            $this->redirect($this->integrationType);
        }
        else
        {
            $this->checkProducts(null, true);
        }
    }

}
