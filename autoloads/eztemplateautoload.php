<?php

use \ezote\operators\Json;

$eZTemplateOperatorArray = array(
    array(
        'script' => 'extension/ezote/operators/json.php',
        'class' => 'ezote\operators\Json',
        'operator_names' => Json::operatorList()
    )
);