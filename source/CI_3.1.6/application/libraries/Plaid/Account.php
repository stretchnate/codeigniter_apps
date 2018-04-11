<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Plaid;

use Plaid\Account\Balances;

/**
 * Class Account
 * @package Plaid\Response
 */
class Account extends Plaid {

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
     * Account constructor.
     * @param $raw_response
     */
    public function __construct($raw_response) {
        parent::__construct($raw_response);
        if(!empty($this->getRawResponse()->account_id)) {
            $this->account_id = $this->getRawResponse()->account_id;
        } elseif(!empty($this->getRawResponse()->id)) {
            $this->account_id = $this->getRawResponse()->id;
        }

        $this->mask = $this->getRawResponse()->mask;
        $this->name = $this->getRawResponse()->name;
        $this->subtype = $this->getRawResponse()->subtype;
        $this->type = $this->getRawResponse()->type;

        if(!empty($this->getRawResponse()->balances)) {
            $this->loadBalances($this->getRawResponse()->balances);
        }
        if(!empty($this->getRawResponse()->official_name)) {
            $this->official_name = $this->getRawResponse()->official_name;
        }


    }

    /**
     * @param $balances
     */
    private function loadBalances($balances) {
        $this->balances = new Balances($balances);
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->account_id;
    }

    /**
     * @return Balances[]
     */
    public function getBalances() {
        return $this->balances;
    }

    /**
     * @return string
     */
    public function getMask() {
        return $this->mask;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getOfficialName() {
        return $this->official_name;
    }

    /**
     * @return string
     */
    public function getSubtype() {
        return $this->subtype;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }
}
