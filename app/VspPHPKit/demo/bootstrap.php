<?php

/**
 * Bootstrap functions
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */

defined('DEMO_PATH') || exit('No direct script access.');


/**
 * Autoload class handler
 *
 * @param string $class
 */
function sagepayDemoAutoloader($class)
{

    $filepath = '';
    for ($i = 0, $n = strlen($class); $i < $n; $i++)
    {
        $char = $class[$i];
        if (preg_match('/[A-Z]/', $char))
        {
            $char = '/' . strtolower($char);
        }
        $filepath .= $char;
    }
    $filename = DEMO_PATH . $filepath . '.php';
    if (file_exists($filename))
    {
        include $filename;
    }
}

/**
 * Exception handler function
 * 
 * @param Exception $ex
 */
function sagepayExceptionHandler(Exception $ex)
{
    SagepayUtil::log("Exception:" . $ex->getMessage(). PHP_EOL . $ex->getTraceAsString());
    if ($ex instanceof SetupException) {
        include_once DEMO_PATH . '/setup-error.php';
    } else {
        include_once DEMO_PATH . '/error.php';
    }
}

spl_autoload_register('sagepayDemoAutoloader');

set_exception_handler('sagepayExceptionHandler');

require_once DEMO_PATH . '/../lib/sagepay.php';

if (!function_exists('url'))
{

    /**
     * Define alias for HelperCommon::url
     * 
     * @return string
     */
    function url()
    {
        $args = func_get_args();
        return call_user_func_array('HelperCommon::url', $args);
    }

}
