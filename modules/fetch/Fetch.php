<?php
namespace ezote\modules\fetch;

use \eZFunctionHandler;

class Fetch extends \ezote\lib\Controller
{
    public function content($type='list')
    {
        $criteria = (array) $_GET['criteria'];
        $objects = eZFunctionHandler::execute('content', $type, $criteria);
        return self::response(compact('objects'), array(
            'type' => 'json'
        ));
    }
}
