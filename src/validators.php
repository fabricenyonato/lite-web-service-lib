<?php
namespace Lib;


class Validator {
    const INTEGER = __NAMESPACE__.'\\integer_validator';
    const DECIMAL = __NAMESPACE__.'\\decimal_validator';
    const NOT_EMPTY = __NAMESPACE__.'\\not_empty_validator';
    const NOT_NULL = __NAMESPACE__.'\\not_null_validator';
}

function integer_validator(&$value) {
    if (is_numeric($value) && preg_match('#^-?\d+$#', $value)) {
        $value = (int) $value;
        return true;
    }
    
    return false;
}

function decimal_validator(&$value) {
    if (is_numeric($value) && preg_match('#^-?\d*\.?\d*$#', $value)) {
        $value = (float) $value;
        return true;
    }

    return false;
}

/**
 * @param string $value
 * @return boolean
 */
function not_empty_validator($value) {
    return strlen("$value") > 0;
}

/**
 * @param mixed $value
 * @return boolean
 */
function not_null_validator($value) {
    return !is_null($value);
}
