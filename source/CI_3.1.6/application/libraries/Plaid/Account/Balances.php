<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Plaid\Account;
use Plaid\Plaid;

/**
 * Class Balances
 * @package Plaid\Response\Account
 */
class Balances extends Plaid {

    /**
     * @var string
     */
    private $available;

    /**
     * @var string
     */
    private $current;

    /**
     * @var string
     */
    private $limit;

    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->available = $this->getRawResponse()->available;
        $this->current = $this->getRawResponse()->current;
        $this->limit = $this->getRawResponse()->limit;
    }

    /**
     * @return string
     */
    public function getAvailable() {
        return $this->available;
    }

    /**
     * @return string
     */
    public function getCurrent() {
        return $this->current;
    }

    /**
     * @return string
     */
    public function getLimit() {
        return $this->limit;
    }
}
