<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Plaid;

/**
 * Class Auth
 * @package Plaid\Response
 */
class Auth {

    /**
     * @var Account[]
     */
    private $accounts = [];

    /**
     * @var Number[]
     */
    private $numbers = [];

    /**
     * @var string
     */
    private $item;

    /**
     * @var string
     */
    private $request_id;

    /**
     * @var stdClass
     */
    private $raw_response;

    /**
     * Auth constructor.
     * @param $raw_response
     */
    public function __construct($raw_response) {
        $this->raw_response = $raw_response;
        $this->parseAccounts($this->raw_response->accounts);
        $this->parseNumbers($this->raw_response->numbers);
        $this->item = $this->raw_response->item;
        $this->request_id = $this->raw_response->request_id;
    }

    /**
     * @param $numbers
     */
    private function parseNumbers($numbers) {
        $nums = [];
        foreach($numbers as $number) {
            $nums[] = new Number($number);
        }

        $this->parseNumbers($nums);
    }

    /**
     * @param $accounts
     */
    private function parseAccounts($accounts) {
        $acct = [];
        foreach($accounts as $account) {
            $acct[] = new Account($account);
        }

        $this->parseAccounts($acct);
    }

    /**
     * @return array
     */
    public function getAccounts() {
        return $this->accounts;
    }

    /**
     * @param array $accounts
     * @return Auth
     */
    public function setAccounts($accounts) {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * @return array
     */
    public function getNumbers() {
        return $this->numbers;
    }

    /**
     * @param array $numbers
     * @return Auth
     */
    public function setNumbers($numbers) {
        $this->numbers = $numbers;
        return $this;
    }

    /**
     * @return string
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * @param string $item
     * @return Auth
     */
    public function setItem($item) {
        $this->item = $item;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestId() {
        return $this->request_id;
    }

    /**
     * @param string $request_id
     * @return Auth
     */
    public function setRequestId($request_id) {
        $this->request_id = $request_id;
        return $this;
    }

    /**
     * @return stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }
}
