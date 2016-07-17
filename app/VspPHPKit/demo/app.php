<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Application Class
 *
 * @category Demo
 * @package  SagepayDemo
 * @copyright (c) 2013, Sagepay Europe Ltd.
 */
class App
{

    /**
     * Database instance
     *
     * @var HelperDatabase
     */
    private $_db;

    /**
     * SagepaySettings Instance
     *
     * @var SagepaySettings
     */
    private $_config;

    /**
     * Application Constructor
     *
     * @param SagepaySettings $config Sagepay configuration object
     */
    public function __construct(SagepaySettings $config = null)
    {

        $this->_db = HelperDatabase::getInstance();
        $this->_config = $config;

        $pathinfo = isset($_SERVER['PATH_INFO']) ? substr($_SERVER['PATH_INFO'], 1) : (isset($_SERVER['ORIG_PATH_INFO']) ? substr($_SERVER['ORIG_PATH_INFO'], 1) : '');
        $path = explode('/', $pathinfo);
        $getController = isset($path[0]) ? $path[0] : null;
        $controller = $this->_createNameAlias($getController);

        $getAction = isset($path[1]) ? $path[1] : null;
        $action = $this->_createNameAlias($getAction);

        $this->_view($controller, $action);
    }

    /**
     * Render view
     *
     * @param string $name      Controller Name without prefix
     * @param string $action    Action Name without prefix
     */
    private function _view($name, $action)
    {
        // Get controller file
        $controllerFile = DEMO_PATH . '/controller/' . strtolower($name) . '.php';
        if (!file_exists($controllerFile))
        {
            throw new Exception('Try to access invalid controller');
        }
        require_once $controllerFile;

        // define controller and actions caller
        $controllerName = 'Controller' . ucfirst($name);
        $actionName = 'action' . ucfirst($action);

        // Create controller and call the required action;
        $controller = new $controllerName();
        $controller->setDbHelper($this->_db);
        $controller->setSagepayConfig($this->_config);
        $controller->before();
        $controller->$actionName();
    }

    /**
     * Private function that provide proper alias for controller and action names
     *
     * @param string $name raw variable that is need to get alias
     *
     * @return string
     */
    private function _createNameAlias($name)
    {
        $name = empty($name) ? 'Index' : $name;
        $parts = preg_split('/[\_\-]/', $name);
        foreach ($parts as $i => $part)
        {
            $parts[$i] = ucfirst($part);
        }
        return implode('', $parts);
    }

}
