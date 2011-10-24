<?php

ini_set('display_errors', 1);
ezote\Autoloader::register();
$router = new \ezote\lib\Router;

$module = $Params['module'];
$extension = $Params['extension'];
$action = $Params['action'] ?: 'index';

$restParams = $Params['Parameters'];

$response = $router->handle($extension, $module, $action, $Params['Parameters']);

$response->run();
