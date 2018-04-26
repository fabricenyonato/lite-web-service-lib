<?php
namespace LiteWebServiceLib;


class Validator {
    const INTEGER = __NAMESPACE__.'\\integer_validator';
    const DECIMAL = __NAMESPACE__.'\\decimal_validator';
    const NOT_EMPTY = __NAMESPACE__.'\\not_empty_validator';
    const NOT_NULL = __NAMESPACE__.'\\not_null_validator';
    const NUMBER_INTERVAL = __NAMESPACE__.'\\number_interval_validator';
    const STRING_LENGTH_INTERVAL = __NAMESPACE__.'\\string_length_interval_validator';
}

/**
 * @param string $value
 * @return boolean
 */
function integer_validator(&$value) {
    if (is_numeric($value) && preg_match('#^-?\d+$#', $value)) {
        $value = (int) $value;
        return true;
    }

    return false;
}

/**
 * @param string $value
 * @return boolean
 */
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

/**
 * @param number $value
 * @param number $min
 * @param number $max
 * @return bool
 */
function number_interval_validator($value, $min, $max) {
    if (!is_null($min) && is_null($max)) {
        return $min < $value;
    }

    if (!is_null($max) && is_null($min)) {
        return $value < $max;
    }

    if (!is_null($min) && !is_null($max)) {
        return $min < $value && $value < $max;
    }

    return true;
}

/**
 * @param string $value
 * @param number $min_length
 * @param number $max_length
 * @return bool
 */
function string_length_interval_validator($value, $min_length, $max_length) {
    $length = mb_strlen($value);

    if (!is_null($min_length) && is_null($max_length)) {
        return $min_length < $length;
    }

    if (!is_null($max_length) && is_null($min_length)) {
        return $length < $max_length;
    }

    if (!is_null($min_length) && !is_null($max_length)) {
        return $min_length < $length && $length < $max_length;
    }

    return true;
}
