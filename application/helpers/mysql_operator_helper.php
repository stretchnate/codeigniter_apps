<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 11/9/2018
 * Time: 11:17 AM
 */

function operator($type, $key, $value1, $value2 = null) {
    switch(strtoupper($type)) {
        case 'BETWEEN':
            return between($key, $value1, $value2);
            break;
        case 'LESSTHAN':
        case '<':
            return lessThan($key, $value1);
        case 'GREATERTHAN':
        case '>':
            return greaterThan($key, $value1);
        case 'LESSTHANEQUALTO':
        case '<=':
            return lessThanEqualTo($key, $value1);
        case 'GREATERTHANEQUALTO':
        case '>=':
            return greaterThanEqualTo($key, $value1);
        case 'NOTEQUAL':
        case '!=':
            return notEqualTo($key, $value1);
        case 'EQUAL':
        case '=':
        default:
            return EqualTo($key, $value1);

    }
}

/**
 * @param $key
 * @param $start
 * @param $end
 * @return string
 */
function between($key, $start, $end) {
    if(!$end) {
        $simple_validation = new SimpleValidation();
        if($simple_validation->isValidDate($start)) {
            $end = date('Y-m-d');
        }
    }

    $result = $key . ' BETWEEN "' . $start . '" AND "' . $end . '"';

    return $result;
}

/**
 * @param $key
 * @param $value
 * @return string
 */
function lessThan($key, $value) {
    return $key . ' < "' . $value . '"';
}

/**
 * @param $key
 * @param $value
 * @return string
 */
function greaterThan($key, $value) {
    return $key . ' > "' . $value . '"';
}

/**
 * @param $key
 * @param $value
 * @return string
 */
function lessThanEqualTo($key, $value) {
    return $key . ' <= "' . $value . '"';
}

/**
 * @param $key
 * @param $value
 * @return string
 */
function greaterThanEqualTo($key, $value) {
    return $key . ' >= "' . $value . '"';
}

/**
 * @param $key
 * @param $value
 * @return string
 */
function equalTo($key, $value) {
    return $key . ' = "' . $value . '"';
}

/**
 * @param $key
 * @param $value
 * @return string
 */
function notEqualTo($key, $value) {
    return $key . ' != "' . $value . '"';
}