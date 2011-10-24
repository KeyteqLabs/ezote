<?php

namespace ezote\lib;

use \eZExecution;

class Controller
{
    public static function response($content = array(), array $options = array())
    {
        return new Response($content, $options);
    }
}
