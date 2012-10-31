<?php

/**
 * EndpointGenerator
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

namespace ezote\lib;

/**
 *
 * Description
 *
 * Generates endpoints for modules.
 *
 * @author Henning Kvinnesland / henning@keyteq.no
 * @since 16.03.2012
 *
 */
class EndpointGenerator
{
    /** @var array Disallowed methods. */
    protected static $blockedMethods = array
    (
        'init', 'response', 'getDefinition', 'getAvailableMethods'
    );

    /** @var array Classes included for autogeneration. True => generate kp-pattern-class/function-name. */
    protected static $classes = array
    (
        'ezexceed\classes\ote\EndpointGenerator' => false,
        'ezexceed\classes\ote\Router' => false,
        'ezexceed\modules\SearchHandler\SearchHandler' => true,
        'ezexceed\modules\activity\Activity' => true,
        'ezexceed\modules\add_content\AddContent' => true,
        'ezexceed\modules\all_content\AllContent' => true,
        'ezexceed\modules\block\Block' => true,
        'ezexceed\modules\device\Device' => true,
        'ezexceed\modules\objectedit\ObjectEdit' => true,
        'ezexceed\modules\pagelayout\Pagelayout' => true,
        'ezexceed\modules\publish\Publish' => true,
        'ezexceed\modules\user_preferences\UserPreferences' => true,
        'ezexceed\modules\nodestructure\NodeStructure' => true,
        'ezexceed\modules\content\Object' => true
    );

    /** @var array List of all available functions. */
    protected static $functionList = array();
    /** @var array List of all endpoint-classes. */
    protected static $endpoints = array();

    /**
     *
     * Creates all available endpoints.
     *
     */
    public static function init()
    {
        foreach (array_keys(self::$classes) as $class)
        {
            $classParts = explode('\\', $class);
            $className = $classParts[count($classParts) - 1];

            self::$endpoints[$class] = $className;

            self::$functionList[$className] = self::getAvailableMethods($class);
        }
    }

    /**
     *
     * Retrieves available endpoint-methods for a given class.
     *
     * @param string $class
     * @param array $options
     * @return array
     */
    public static function getAvailableMethods($class, array $options = array())
    {
        $options += array('public' => true);
        $reflector = new \ReflectionClass($class);
        $methods = $reflector->getMethods();
        $validMethods = array();

        /** @var $method \ReflectionMethod */
        foreach ($methods as $method)
        {
            /** Only public methods. */
            if ($options['public'] && !$method->isPublic())
                continue;

            /** No inherited methods as endpoints. */
            if ($method->getDeclaringClass()->getName() !== $class)
                continue;

            /** Do not include blocked methods. */
            if (in_array($method->getName(), self::$blockedMethods))
                continue;

            $validMethods[] = $method->getName();
        }

        return $validMethods;
    }

    public static function writeEndpoints()
    {
        self::init();

        echo '<pre>';

        foreach(self::$functionList as $className => $classFunctions)
            foreach($classFunctions as $function)
                echo 'FunctionList[]=' . $className . '_' . $function . "\n";

        echo "\n";

        foreach(self::$endpoints as $endPoint => $className)
        {
            foreach(self::$functionList[$className] as $function)
            {
                $namespace = array('class' => $className);

                if (self::$classes[$endPoint] !== false)
                    $namespace['function'] = $function;

                echo '[ezjscServer_' . implode('_', $namespace) . ']' . "\n";

                echo 'Class=' . $endPoint . "\n\n";

                $namespace['function'] = $function;
                echo 'Functions[]=' . implode('_', $namespace) . "\n\n";
            }
        }

        echo '</pre>';

        \eZExecution::cleanExit();
    }
}
