<?php

/**
 * Response-controller
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

namespace ezote\lib;

use \ezote\lib\EndpointGenerator;

/** Response-controller. */
class Controller
{
    /** @var \eZHTTPTool */
    public static $http;

    public static function init()
    {
        self::$http = \eZHTTPTool::instance();
    }

    /**
     *
     * Helper to create a `Response` object
     *
     * @param mixed $content
     * @param array $options
     *
     * @return Response
     *
     */
    public static function response($content = array(), array $options = array())
    {
        $response = new Response($content, $options);
        return $response->run();
    }

    /**
     * Build the needed information for module setup
     *
     * @param array $merge
     * @return array Three members: Module, FunctionList and ViewList
     */
    public static function getDefinition(array $merge = array(), array $options = array())
    {
        $class = get_called_class();
        $classParts = explode('\\', $class);
        $className = array_pop($classParts);
        $ViewList = array();
        $validMethods = EndpointGenerator::getAvailableMethods($class, $options);

        foreach ($validMethods as $view)
        {
            $ViewList[$view] = array('script' => 'module.php');

            if (isset($merge['ViewList']))
                $ViewList[$view] += $merge['ViewList'];
        }

        $Module = array('name' => $className);
        $FunctionList = array();

        return array($Module, $FunctionList, $ViewList);
    }
}

Controller::init();
