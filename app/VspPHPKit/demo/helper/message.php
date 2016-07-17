<?php

defined('DEMO_PATH') || exit('No direct script access.');

/**
 * Helper class to generate automatically messages
 *
 * @category  Payment
 * @package   SagepayDemo
 * @copyright (c) 2013, Sage Pay Europe Ltd.
 */
class HelperMessage
{

    /**
     * Associated array of messages for validation's errors
     *
     * @var type
     */
    private $_messages = array();

    /**
     * Constructor for HelperMessage
     */
    public function __construct()
    {
        $this->_messages = $this->_loadMessages();
    }

    /**
     * Initialization messages
     *
     * @return array
     */
    private function _loadMessages()
    {
        return include_once DEMO_PATH . '/messages.php';
    }

    /**
     * Generate all error messages
     *
     * @param array $errors
     * @param mixed $fields
     *
     * @return string
     */
    public function getAllMessages(array $errors, $fields = null)
    {
        $messages = array();
        foreach ($errors as $field => $error)
        {
            foreach ($error as $line)
            {
                if (isset($this->_messages[$line]))
                {
                    $arg = isset($fields[$field]) ? $fields[$field] : $field;
                    $messages[] = $this->getMessage($line, $arg);
                }
            }
        }
        return implode(', ', $messages);
    }

    /**
     * Get single message
     *
     * @param  string  $alias   Alias of message
     * @param  array   $args    Arguments used for message
     *
     * @return string
     */
    public function getMessage($alias, $args = array())
    {
        if (isset($this->_messages[$alias]))
        {
            return sprintf($this->_messages[$alias], $args);
        }
        else
        {
            return sprintf($alias, $args);
        }
    }

}
