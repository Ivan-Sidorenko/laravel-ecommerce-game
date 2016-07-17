<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Helper class to render the views
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class HelperView
{
    /**
     * @const VIEWS_PATH Path to templates
     */
    const VIEWS_PATH = 'views';

    /**
     * @const LAYOUTS_PATH Path to layouts
     */
    const LAYOUTS_PATH = 'views/layouts';

    /**
     * Layout name to be displaied
     *
     * @var string
     */
    protected $layout = 'layout';

    /**
     * The path to template to be displaied
     *
     * @var string
     */
    protected $path;

    /**
     * Associated array of data and values to be dispalied on page.
     * Used in layouts and templates.
     *
     * @var array
     */
    protected $data = array();

    /**
     * Constructor for HelperView
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Set layout
     *
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Set data
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Display layout
     */
    public function render()
    {
        extract($this->data);
        require_once DEMO_PATH . '/' . self::LAYOUTS_PATH . '/' . $this->layout . '.tpl';
    }

    /**
     * Display template
     *
     * @param string $path
     */
    public function renderContent($path = null)
    {
        extract($this->data);
        require_once DEMO_PATH . '/' . self::VIEWS_PATH . '/' . ($path ? $path : $this->path) . '.tpl';
    }

}