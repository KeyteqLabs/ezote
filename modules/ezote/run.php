<?php

\ezote\Autoloader::register();
$router = new \ezote\lib\Router;

$Params += array('module' => false, 'extension' => 'ezote', 'action' => 'index');

$functionName = $Params['FunctionName'];
$module = $Params['module'] ?: $functionName;
$extension = $Params['extension'];
$action = $Params['action'];

$originalParams = $Params['Module']->Functions[$functionName]['params'];

$restParams = array_slice($Params['Parameters'], count($originalParams));

$response = $router->handle($extension, $module, $action, $restParams);

$Result = $response->run();
