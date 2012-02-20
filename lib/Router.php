<?php

namespace ezote\lib;

class Router
{
    /**
     * Route a delegate uri to a different extensions modules
     * @param string $module
     * @param string $action
     * @param array $params
     * @return \ezote\lib\Response;
     */
    public function handle($extension, $module, $action, $params)
    {
        $class = Inflector::camelize($module);
        $nsClass = join('\\', array($extension, 'modules', $module, $class));
        // TODO Pass in context in constructor?
        trigger_error($nsClass . ' ' . $class . ' ' . $action);
        $controller = new $nsClass();
        $response = call_user_func_array(array($controller, $action), $params);
        if (!($response instanceof \ezote\lib\Response))
        {
            $response = new Response((array) $response);
        }
        return $response;
    }
}
