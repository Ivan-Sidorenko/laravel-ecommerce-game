<?php

define('DEMO_PATH', dirname(__FILE__));
define('BASE_PATH', substr($_SERVER['SCRIPT_NAME'], 0, strlen($_SERVER['SCRIPT_NAME']) - 9));
define('CLIENT_EXEC', true);
require_once DEMO_PATH . '/bootstrap.php';

$cron = new ControllerCrontasks();
$cron->run();
