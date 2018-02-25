<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Plaid\Auth\Response;

use Plaid\Auth\Response\Account\Balances;

/**
 * Class Account
 * @package Plaid\Response
 */
class Account {

    /**
     * @var int
     */
    private $account_id;

    /**
     * @var Balances[]
     */
    private $balances = [];

    /**
     * @var string
     */
    private $mask;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $official_name;

    /**
     * @var string
     */
    private $subtype;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \stdClass
     */
    private $raw_response;

    /**
     * Account constructor.
     * @param $raw_response
     */
    public function __construct($raw_response) {
        $this->raw_response = $raw_response;
        $this->setAccountId($this->raw_response->account_id);
        $this->parseBalances($this->raw_response->balances);
        $this->setMask($this->raw_response->mask);
        $this->setName($this->raw_response->name);
        $this->setOfficialName($this->raw_response->official_name);
        $this->setSubtype($this->raw_response->subtype);
        $this->setType($this->raw_response->type);
    }

    /**
     * @param $balances
     */
    private function parseBalances($balances) {
        foreach($balances as $balance) {
            $this->balances[] = new Balances($balance);
        }
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->account_id;
    }

    /**
     * @param int $account_id
     * @return Account
     */
    public function setAccountId($account_id) {
        $this->account_id = $account_id;
        return $this;
    }

    /**
     * @return Balances[]
     */
    public function getBalances() {
        return $this->balances;
    }

    /**
     * @param Balances[] $balances
     * @return Account
     */
    public function setBalances($balances) {
        $this->balances = $balances;
        return $this;
    }

    /**
     * @return string
     */
    public function getMask() {
        return $this->mask;
    }

    /**
     * @param string $mask
     * @return Account
     */
    public function setMask($mask) {
        $this->mask = $mask;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Account
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getOfficialName() {
        return $this->official_name;
    }

    /**
     * @param string $official_name
     * @return Account
     */
    public function setOfficialName($official_name) {
        $this->official_name = $official_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubtype() {
        return $this->subtype;
    }

    /**
     * @param string $subtype
     * @return Account
     */
    public function setSubtype($subtype) {
        $this->subtype = $subtype;
        return $this;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Account
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }

}
