<?php

/**
 * Router
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
 * Router for ote.
 *
 * @author Henning Kvinnesland / henning@keyteq.no
 * @since 19.03.2012
 *
 */

class Router
{
    /** @var string The callback in question. */
    protected static $callback;
    /** @var array The function arguments. */
    protected static $parsedArgs;
    /** @var string Handles occasional resets of the token. */
    protected static $ezxFormToken;

    /**
     *
     * Validates and parses the arguments.
     *
     * @param $args array
     *
     * @return bool
     *
     */
    protected static function parseArgs($args)
    {
        if (isset($args[0]) && isset($args[1]))
        {
            $className = array_shift($args);
            $methodName = array_shift($args);

            $ezjscoreIni = \eZINI::instance('ezjscore.ini');
            $iniNamespace = $className . '_' . $methodName;

            if ($ezjscoreIni->hasVariable('ezjscServer_' . $iniNamespace, 'Class')) {
                $className = $ezjscoreIni->variable('ezjscServer_' . $iniNamespace, 'Class');

                self::$callback = array($className, $methodName);
                self::$parsedArgs = $args;

                return is_callable(self::$callback);
            }
        }

        return false;
    }

    /**
     *
     * Handles incoming server-requests.
     *
     * @param $args array
     *
     * @return mixed
     *
     */
    public static function handle($args)
    {
        self::handleEZXFormToken();

        $response = null;

        if (self::parseArgs($args))
            $response = call_user_func_array(self::$callback, self::$parsedArgs);
        /** Legacy-support. */
        else
            $response = call_user_func_array(array('self', 'handleLegacy'), func_get_args());

        self::handleEZXFormToken(true);

        return $response;
    }

    /**
     * Route a delegate uri to a different extensions modules
     *
     * @param $extension
     * @param string $module
     * @param string $action
     * @param array $params
     *
     * @return \ezote\lib\Response;
     */
    public function legacyHandle($extension, $module, $action, $params)
    {
        self::handleEZXFormToken();

        $class = Inflector::camelize($module);
        $nsClass = join('\\', array($extension, 'modules', $module, $class));
        // TODO Pass in context in constructor?
        $controller = new $nsClass();
        $response = call_user_func_array(array($controller, $action), $params);
        if (!($response instanceof \ezote\lib\Response))
            $response = new Response($response);

        self::handleEZXFormToken(true);
        return $response;
    }

    /**
     *
     * Handles casese where the token is reset during a request.
     *
     * @param bool $restore
     *
     * @return bool
     *
     */
    public static function handleEZXFormToken($restore = false)
    {
        $activeExtensions = \eZExtension::activeExtensions();

        if (in_array('ezformtoken', $activeExtensions))
        {
            if ($restore)
            {
                if (isset(self::$ezxFormToken) &&!empty(self::$ezxFormToken))
                    \eZSession::set(\ezxFormToken::SESSION_KEY, self::$ezxFormToken);
            }
            else
                self::$ezxFormToken= \eZSession::get(\ezxFormToken::SESSION_KEY);

            return self::$ezxFormToken;
        }

        return false;
    }

    /**
     * Return form token
     */
    public static function getEzxFormToken()
    {
        if (empty(self::$ezxFormToken) || self::$ezxFormToken == '')
            return false;

        return self::$ezxFormToken;
    }

    /**
     *
     * Wrapper for handling of modules.
     *
     * @param $params
     *
     * @return mixed
     *
     */
    public static function handleModule($params)
    {
        $moduleName = $params['Module']->Module['name'];
        $functionName = $params['FunctionName'];

        $callback = array($moduleName, $functionName);

        $args = array_merge($callback, $params['Parameters']);

        return self::handle($args);
    }
}
