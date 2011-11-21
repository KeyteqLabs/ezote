<?php
/**
 * eZ on the Edge
 *
 * @copyright     Copyright 2011, Keyteq AS (http://keyteq.no/labs)
 * @license       http://opensource.org/licenses/mit-license.php The MIT License
 */

namespace ezote\lib;

/**
 * Inflector is a utility to camelize and underscore words
 */
class Inflector
{
    /**
     * Camelize or camelback a word
     *
     * @param string $word
     * @param bool $cased
     * @return string
     */
    public static function camelize($word, $cased = true)
    {
        $word = str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', $word)));
        return $cased ? $word : lcfirst($word);
    }

    /**
     * Transform a camelized word to underscored
     *
     * @param string $word
     * @return string
     */
    public static function underscore($word) {
        return strtolower(preg_replace('#([a-z])([A-Z])#', '\\1_\\2', $word));
    }
}
