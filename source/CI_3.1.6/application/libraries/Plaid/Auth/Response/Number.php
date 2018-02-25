<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/22/18
 * Time: 6:54 PM
 */

namespace Plaid\Auth\Response;


/**
 * Class Number
 * @package Plaid\Response
 */
class Number {

    /**
     * @var string
     */
    private $account;

    /**
     * @var string
     */
    private $account_id;

    /**
     * @var string
     */
    private $routing;

    /**
     * @var string
     */
    private $wire_routing;

    /**
     * @var \stdClass
     */
    private $raw_response;

    /**
     * Number constructor.
     * @param $raw_response
     */
    public function __construct($raw_response) {
        $this->raw_response = $raw_response;
        $this->setAccount($this->raw_response->account);
        $this->setAccountId($this->raw_response->account_id);
        $this->setRouting($this->raw_response->routing);
        $this->setWireRouting($this->raw_response->wire_routing);
    }


    /**
     * @return string
     */
    public function getAccount() {
        return $this->account;
    }

    /**
     * @param string $account
     * @return \Plaid\Response\Number
     */
    public function setAccount($account) {
        $this->account = $account;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountId() {
        return $this->account_id;
    }

    /**
     * @param string $account_id
     * @return \Plaid\Response\Number
     */
    public function setAccountId($account_id) {
        $this->account_id = $account_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getRouting() {
        return $this->routing;
    }

    /**
     * @param string $routing
     * @return \Plaid\Response\Number
     */
    public function setRouting($routing) {
        $this->routing = $routing;
        return $this;
    }

    /**
     * @return string
     */
    public function getWireRouting() {
        return $this->wire_routing;
    }

    /**
     * @param string $wire_routing
     * @return \Plaid\Response\Number
     */
    public function setWireRouting($wire_routing) {
        $this->wire_routing = $wire_routing;
        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }

}