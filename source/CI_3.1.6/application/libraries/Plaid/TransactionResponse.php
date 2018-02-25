<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 7:02 PM
 */

namespace Plaid;


use Plaid\Auth\Response\Account;
use Plaid\TransactionResponse\Transaction;

/**
 * Class TransactionResponse
 *
 * @package Plaid
 */
class TransactionResponse {

    /**
     * @var Account[]
     */
    private $accounts;

    /**
     * @var Transaction[]
     */
    private $transactions;

    /**
     * @var object
     */
    private $item;

    /**
     * @var int
     */
    private $total_transactions;

    /**
     * @var string
     */
    private $request_id;

    /**
     * @var \stdClass
     */
    private $raw_response;

    /**
     * TransactionResponse constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        $this->raw_response = $raw_response;

        $this->loadAccounts($this->raw_response->accounts);
        $this->loadTransactions($this->raw_response->transactions);

        $this->item = $this->raw_response->item;
        $this->total_transactions = $this->raw_response->total_transactions;
        $this->request_id = $this->raw_response->request_id;
    }

    /**
     * @param array $raw_accounts
     */
    public function loadAccounts(array $raw_accounts) {
        foreach($raw_accounts as $account) {
            $this->accounts[] = new Account($account);
        }
    }


    /**
     * @param array $raw_transactions
     */
    public function loadTransactions(array $raw_transactions) {
        foreach($raw_transactions as $transaction) {
            $this->transactions[] = new Transaction($transaction);
        }
    }

    /**
     * @return Account[]
     */
    public function getAccounts() {
        return $this->accounts;
    }

    /**
     * @param Account[] $accounts
     * @return TransactionResponse
     */
    public function setAccounts($accounts) {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions() {
        return $this->transactions;
    }

    /**
     * @param Transaction[] $transactions
     * @return TransactionResponse
     */
    public function setTransactions($transactions) {
        $this->transactions = $transactions;

        return $this;
    }

    /**
     * @return object
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * @param object $item
     * @return TransactionResponse
     */
    public function setItem($item) {
        $this->item = $item;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalTransactions() {
        return $this->total_transactions;
    }

    /**
     * @param int $total_transactions
     * @return TransactionResponse
     */
    public function setTotalTransactions($total_transactions) {
        $this->total_transactions = $total_transactions;

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
     * @return TransactionResponse
     */
    public function setRequestId($request_id) {
        $this->request_id = $request_id;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }

}