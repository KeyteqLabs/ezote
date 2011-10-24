<?php

namespace ezote\lib;

use \eZExecution;

class Response
{
    protected $content = array();
    protected $options = array();

    public function __construct($content = array(), array $options = array())
    {
        $this->content = $content;
        $this->options = $options;
    }

    public function run()
    {
        $options = $this->options + array(
            'type' => 'tpl',
            'headers' => array()
        );
        $content = $this->content;

        switch ($options['type'])
        {
            case 'json':
                $options['headers'] += array(
                    'Content-type' => 'application/json'
                );
                static::_headers($options['headers']);
                echo json_encode($content);
                eZExecution::cleanExit();
                break;
        }
    }

    protected static function _headers($headers)
    {
        foreach ($headers as $name => $value)
            header($name . ': ' . $value);
    }
}
