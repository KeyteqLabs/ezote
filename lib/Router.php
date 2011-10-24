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
        $nsClass = join('\\', array($extension, 'modules', $module, $module));
        // TODO Pass in context in constructor?
        $controller = new $nsClass();
        $response = call_user_func_array(array($controller, $action), $params);
        if (!($response instanceof \ezote\lib\Response))
        {
            $response = new Response((array) $response);
        }
        return $response;
    }
}
