<?php

/**
 * Handles module-respons
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

namespace ezote\lib;

/** Handles module-response, */
class Response
{
    /** @var array Response-contents. */
    protected $content = array();
    /** @var array Response-options. */
    protected $options = array();

    /** @var array Complete list of HTTP codes mapped to their text counter part. */
    protected $codes = array
    (
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );

    /**
     *
     * Creates a new object-instance.
     *
     * @param array $content
     * @param array $options
     *
     */
    public function __construct($content = array(), array $options = array())
    {
        $this->content = $content;
        $this->options = $options + array
        (
            'status' => 200,
            'type' => 'tpl',
            'headers' => array()
        );
    }

    /**
     *
     * Builds the response-data.
     *
     * @return mixed|void
     *
     */
    public function run()
    {
        $content = $this->content;
        $status = $this->options['status'];
        $options = $this->options;

        // Give response header for status code
        header('HTTP/1.1 ' . $status . ' ' . $this->codes[$status]);

        switch ($options['type'])
        {
            case 'json':
                $options['headers'] += array('Content-type' => 'application/json');
                $this->headers($options['headers']);
                echo json_encode($content);
                break;
            case 'jsonEncoded':
                $options['headers'] += array('Content-type' => 'application/json');
                $this->headers($options['headers']);
                return compact('content');
            case 'tpl':
                /** Default pagelayout. */
                $options += array('pagelayout' => 'pagelayout.tpl');
                return $this->renderTpl($content, $options);
            case 'text':
                return array
                (
                    'pagelayout' => false,
                    'content' => $this->content
                );
            case 'xml' :
                $options['headers'] += array('Content-type' => 'text/xml');
                $this->headers($options['headers']);
                echo $content;
                break;

        }

        Router::handleEZXFormToken(true);

        return \eZExecution::cleanExit();
    }

    /**
     *
     * Renders a template.
     *
     * @param $content
     * @param array $options
     *
     * @return array
     *
     */
    protected function renderTpl($content, array $options = array())
    {
        if (isset($options['template']))
        {
            $tpl = \eZTemplate::factory();
            if (is_array($content))
            {
                foreach ($content as $key => $val)
                    $tpl->setVariable($key, $val);
            }
            $content = $tpl->fetch($options['template']);
            unset($options['template']);
        }
        return compact('content') + $options;
    }

    /**
     *
     * Outputs headers.
     *
     * @param $headers
     *
     */
    protected function headers($headers)
    {
        foreach ($headers as $name => $value)
            header($name . ': ' . $value);
    }
}
