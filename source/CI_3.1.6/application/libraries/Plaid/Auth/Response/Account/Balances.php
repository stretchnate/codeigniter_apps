<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Plaid\Auth\Response\Account;

/**
 * Class Balances
 * @package Plaid\Response\Account
 */
class Balances {

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

    private $raw_response;

    public function __construct($raw_response) {
        $this->raw_response = $raw_response;
        $this->setAvailable($this->raw_response->available);
        $this->setCurrent($this->raw_response->current);
        $this->setLimit($this->raw_response->limit);
    }

    /**
     * @return string
     */
    public function getAvailable() {
        return $this->available;
    }

    /**
     * @param $available
     * @return $this
     */
    public function setAvailable($available) {
        $this->available = $available;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrent() {
        return $this->current;
    }

    /**
     * @param $current
     * @return $this
     */
    public function setCurrent($current) {
        $this->current = $current;

        return $this;
    }

    /**
     * @return string
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function setLimit($limit) {
        $this->limit = $limit;

        return $this;
    }
}
