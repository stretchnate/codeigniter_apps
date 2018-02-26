<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 9:49 PM
 */

namespace Plaid\IdentityResponse\Identity;


use Plaid\Location;
use Plaid\Plaid;

class Address extends Plaid {

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

    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->accounts = $this->getRawResponse()->accounts;
        $this->data = new Location($this->getRawResponse()->data);
        $this->primary = $this->getRawResponse()->primary;
    }

    /**
     * @return array
     */
    public function getAccounts() {
        return $this->accounts;
    }

    /**
     * @return Location
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isPrimary() {
        return $this->primary;
    }
}