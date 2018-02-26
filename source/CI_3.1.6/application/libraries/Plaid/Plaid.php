<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/25/18
 * Time: 6:37 PM
 */

namespace Plaid;


/**
 * Class Plaid
 *
 * @package Plaid
 */
abstract class Plaid {

    /**
     * @var \stdClass
     */
    protected $raw_response;

    /**
     * Plaid constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        $this->raw_response = $raw_response;
    }

    /**
     * @return \stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }

}