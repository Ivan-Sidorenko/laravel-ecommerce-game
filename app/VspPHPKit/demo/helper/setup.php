<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Setup helper functions
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class HelperSetup
{

    private static function checkPhpExtentionIsLoaded($name) {
        if (!extension_loaded($name)) {
            throw new SetupException("$name not loaded.");
        }
    }

    public static function checkDependencies() {
        HelperSetup::checkPhpExtentionIsLoaded('mcrypt');
        HelperSetup::checkPhpExtentionIsLoaded('curl');
        HelperSetup::checkPhpExtentionIsLoaded('mysql');
        HelperSetup::checkPhpExtentionIsLoaded('pdo');
        HelperSetup::checkPhpExtentionIsLoaded('pdo_mysql');
        HelperSetup::checkPhpExtentionIsLoaded('json');
    }

}

class SetupException extends Exception {}
