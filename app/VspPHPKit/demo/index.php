<?php

/**
 * Start new or resume existing session
 */
session_start();

define('DEMO_PATH', dirname(__FILE__));
define('BASE_PATH', substr($_SERVER['SCRIPT_NAME'], 0, strlen($_SERVER['SCRIPT_NAME']) - 9));
require_once DEMO_PATH . '/bootstrap.php';

HelperSetup::checkDependencies();

// Is required for initialiize the the SagePay Api
$config = SagepaySettings::getInstance();
new App($config);
