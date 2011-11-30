<?php
/**
 * eZ on the Edge
 *
 * @copyright     Copyright 2011, Keyteq AS (http://keyteq.no/labs)
 * @license       http://opensource.org/licenses/mit-license.php The MIT License
 * @author        Raymond Julin <raymond@keyteq.no>
 */

namespace ezote\lib;

use \eZHTTPTool;

/**
 * HTTP Helper for the bits and pieces eZHTTPTool does not help out with
 */
class HTTP
{

    /**
     * Maintain singleton
     * @var object
     */
    protected static $_instance = false;

    /**
     * Reference eZHTTPTool
     * @var \eZHTTPTool
     */
    protected $_ez = null;

    /**
     * Original server data
     * @var array
     */
    protected $server = array();

    /**
     * Construct HTTP
     * 
     * @param object $eZHTTP
     * @param array $server Equals PHPs $_SERVER
     * @return \ezote\lib\HTTP
     */
    protected function __construct($eZHTTP, $server)
    {
        $this->_ez = $eZHTTP;
        $this->server = $server;
    }

    /**
     * Get HTTP instance
     *
     * @return \ezote\lib\HTTP
     */
    public static function instance()
    {
        if (static::$_instance === false)
            static::$_instance = new static(eZHTTPTool::instance(), $_SERVER);
        return static::$_instance;
    }

    /**
     * What type of HTTP call was issued
     *
     * @return string GET, POST, DELETE, PUT, HEAD or OPTION
     */
    public function method($is = false)
    {
        $value = $this->server['REQUEST_METHOD'];
        return is_string($is) ? strtolower($is) === strtolower($value) : $value;
    }

    /**
     * Get the eZPublish HTTP tool
     *
     * @return eZHTTPTool
     */
    public function ez()
    {
        return $this->_ez;
    }
}
