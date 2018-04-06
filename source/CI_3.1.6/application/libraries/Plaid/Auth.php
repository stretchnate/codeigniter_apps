<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Plaid;

use Plaid\Auth\Account;
use Plaid\Auth\Number;
/**
 * Class Auth
 * @package Plaid\Response
 */
class Auth extends Plaid {

    use RequestId, Item;

    /**
     * @var Account[]
     */
    private $accounts = [];

    /**
     * @var Number[]
     */
    private $numbers = [];

    /**
     * Auth constructor.
     * @param $raw_response
     */
    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->loadAccounts($this->getRawResponse()->accounts);
        $this->loadNumbers($this->getRawResponse()->numbers);
        $this->setRequestId($this->getRawResponse()->request_id);
        $this->setItem($this->getRawResponse()->item);
    }

    /**
     * @param $numbers
     */
    private function loadNumbers($numbers) {
        foreach($numbers as $number) {
            $this->numbers[] = new Number($number);
        }
    }

    /**
     * @param $accounts
     */
    private function loadAccounts($accounts) {
        foreach($accounts as $account) {
            $this->accounts[] = new Account($account);
        }
    }

    /**
     * @return array
     */
    public function getAccounts() {
        return $this->accounts;
    }

    /**
     * @return array
     */
    public function getNumbers() {
        return $this->numbers;
    }
}
