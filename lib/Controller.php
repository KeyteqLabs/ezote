<?php
/**
 * eZ on the Edge
 *
 * @copyright     Copyright 2011, Keyteq AS (http://keyteq.no/labs)
 * @license       http://opensource.org/licenses/mit-license.php The MIT License
 */

namespace ezote\lib;

use \eZExecution;

/**
 * Automapped web controller that has every public method
 * made available under the /ezote/delegate/<extension>/<controller>
 */
class Controller
{
    /**
     * Helper to create a `Response` object
     * @param mixed $content
     * @param array $options
     * @return \ezote\lib\Response
     */
    public static function response($content = array(), array $options = array())
    {
        return new Response($content, $options);
    }
}
