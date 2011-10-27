<?php

$Module = array(
    'name' => 'ezote'
);

$ViewList = array(
    'delegate' => array(
        'script' => 'run.php',
        'params' => array('extension', 'module', 'action')
    ),
    'fetch' => array(
        'script' => 'run.php',
        'params' => array('action')
    )
);

$FunctionList = array();

