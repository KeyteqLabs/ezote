<?php

ezote\Autoloader::register();
$router = new \ezote\lib\Router;

$module = $Params['module'];
$extension = $Params['extension'];
$action = $Params['action'] ?: 'index';

$restParams = array_slice($Params['Parameters'], 3);

$response = $router->handle($extension, $module, $action, $restParams);

$Result = $response->run();
