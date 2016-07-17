<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Controller for front page
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class ControllerIndex extends ControllerAbstract
{

    /**
     * Initialization default variables
     */
    public function __construct()
    {
        $this->layout = 'front';
    }

    /**
     * Action index front page
     */
    public function actionIndex()
    {
        $view = new HelperView('index/view');
        $view->setLayout($this->layout);
        $view->render();
    }

}
