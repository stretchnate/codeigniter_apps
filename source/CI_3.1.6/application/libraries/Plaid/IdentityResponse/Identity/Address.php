<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 9:49 PM
 */

namespace Plaid\IdentityResponse\Identity;


use Plaid\Location;

class Address {

    /**
     * @var array
     */
    private $accounts;

    /**
     * @var Location
     */
    private $data;

    /**
     * @var bool
     */
    private $primary;

    /**
     * @var \stdClass
     */
    private $raw_response;

    public function __construct($raw_response) {
        $this->raw_response = $raw_response;
        $this->accounts = $this->raw_response->accounts;
        $this->data = new Location($this->raw_response->data);
        $this->primary = $this->raw_response->primary;
    }
}