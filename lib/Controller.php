<?php
/**
 * eZ on the Edge
 *
 * @copyright     Copyright 2011, Keyteq AS (http://keyteq.no/labs)
 * @license       http://opensource.org/licenses/mit-license.php The MIT License
 */

namespace ezote\lib;

use \eZExecution;

/**
 * Automapped web controller that has every public method
 * made available under the /ezote/delegate/<extension>/<controller>
 */
class Controller
{
    /**
     * Helper to create a `Response` object
     * @param mixed $content
     * @param array $options
     * @return \ezote\lib\Response
     */
    public static function response($content = array(), array $options = array())
    {
        return new Response($content, $options);
    }

    /**
     * Build the needed information for module setup
     *
     * @return array Three members: Module, FunctionList and ViewList
     */
    public static function getDefinition()
    {
        $class = get_called_class();
        $classParts = explode('\\', $class);
        $className = array_pop($classParts);
        $ViewList = array();
        foreach (get_class_methods($class) as $view)
        {
            $ViewList[$view] = array('script' => 'module.php');
        }

        $Module = array(
            'name' => Inflector::underscore($className)
        );
        $FunctionList = array();

        return array($Module, $FunctionList, $ViewList);
    }
}
