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
                return eZExecution::cleanExit();
                break;
            case 'tpl':
                $options += array(
                    'pagelayout' => 'pagelayout.tpl'
                );
                return static::_renderTpl($content, $options);
        }
    }

    protected static function _renderTpl($content, array $options = array())
    {
        $result = array(
            'pagelayout' => $options['pagelayout']
        );
        if (isset($options['template']))
        {
            $tpl = \eZTemplate::factory();
            if (is_array($content))
            {
                foreach ($content as $key => $val)
                    $tpl->assignValue($key, $val);
            }
            $content = $tpl->fetch($options['template']);
        }
        $result['content'] = $content;
        return $result;
    }

    protected static function _headers($headers)
    {
        foreach ($headers as $name => $value)
            header($name . ': ' . $value);
    }
}
