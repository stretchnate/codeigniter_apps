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
    private $raw_response;

    /**
     * Plaid constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        $this->raw_response = $raw_response;
        $this->item = isset($raw_response->item) ? $raw_response->item : null;
        $this->request_id = isset($raw_response->request_id) ? $raw_response->request_id : null;
    }

    /**
     * @return \stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }
}