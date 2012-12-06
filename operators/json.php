<?php
/**
 * JSON encode operator
 *
 * @copyright //autogen//
 * @license //autogen//
 * @version //autogen//
 */

namespace ezote\operators;


/**
 * JSON encode an array
 */
class Json
{
    const OPERATOR_JSON = 'json';

    /**
     * Return an array with the template operator(s).
     *
     * @return array
     */
    public static function operatorList()
    {
        return array(self::OPERATOR_JSON);
    }

    /**
     * Executes the PHP function for the operator cleanup and modifies $operatorValue.
     *
     *
     * @param \eZTemplate $tpl
     * @param string $operatorName
     * @param array $operatorParameters
     * @param string $rootNamespace
     * @param string $currentNamespace
     * @param mixed $operatorValue
     */
    public function modify($tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue)
    {
        switch ($operatorName)
        {
            case self::OPERATOR_JSON:
                $operatorValue = json_encode($operatorValue);
                break;
        }
    }
}
