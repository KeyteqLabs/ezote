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
     * Contains a default map of accented and special characters to ASCII characters.
     *
     * @see ezote\lib\Inflector::slug()
     * @var array
     */
    protected static $_transliteration = array(
        '/à|á|å|â/' => 'a',
        '/è|é|ê|ẽ|ë/' => 'e',
        '/ì|í|î/' => 'i',
        '/ò|ó|ô|ø/' => 'o',
        '/ù|ú|ů|û/' => 'u',
        '/ç/' => 'c', '/ñ/' => 'n',
        '/ä|æ/' => 'ae', '/ö/' => 'oe',
        '/ü/' => 'ue', '/Ä/' => 'Ae',
        '/Ü/' => 'Ue', '/Ö/' => 'Oe',
        '/ß/' => 'ss'
    );

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


    /**
     * Returns a string with all spaces converted to given replacement and
     * non word characters removed.  Maps special characters to ASCII using
     * `Inflector::$_transliteration`
     *
     * @param string $string An arbitrary string to convert.
     * @param string $replacement The replacement to use for spaces.
     * @return string The converted string.
     */
    public static function slug($string, $replacement = '-') {
        $map = static::$_transliteration + array(
            '/[^\w\s]/' => ' ', '/\\s+/' => $replacement,
            '/(?<=[a-z])([A-Z])/' => $replacement . '\\1',
            str_replace(':rep', preg_quote($replacement, '/'), '/^[:rep]+|[:rep]+$/') => ''
        );
        return preg_replace(array_keys($map), array_values($map), $string);
    }

}
