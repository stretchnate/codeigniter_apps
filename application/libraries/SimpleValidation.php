<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/4/18
 * Time: 9:36 PM
 */

class SimpleValidation {

    /**
     * @param $value
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function isInt($value) {
        if(!is_int($value)) {
            throw new InvalidArgumentException("$value is not an integer.", EXCEPTION_CODE_VALIDATION);
        }

        return $value;
    }

    /**
     * @param $value
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function isNumeric($value) {
        if(!is_numeric($value)) {
            throw new InvalidArgumentException("$value is not numeric.", EXCEPTION_CODE_VALIDATION);
        }

        return $value;
    }

    /**
     * @param $value
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function isString($value) {
        if(!is_string($value)) {
            throw new InvalidArgumentException("$value is not a string.", EXCEPTION_CODE_VALIDATION);
        }

        return $value;
    }

    /**
     * @param $value
     * @return bool
     */
    public function isValidDate($value) {
        $result = $value;
        try{
            new \DateTime($value);
        } catch(Exception $e) {
            $result = false;
        }

        return $result;
    }
}