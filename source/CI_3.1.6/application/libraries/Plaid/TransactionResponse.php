<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 7:02 PM
 */

namespace Plaid;


use Plaid\Auth\Account;
use Plaid\TransactionResponse\Transaction;

/**
 * Class TransactionResponse
 *
 * @package Plaid
 */
class TransactionResponse extends Plaid {

    use RequestId, Item;

    /**
     * @var Account[]
     */
    private $accounts;

    /**
     * @var Transaction[]
     */
    private $transactions;

    /**
     * @var int
     */
    private $total_transactions;

    /**
     * TransactionResponse constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        parent::__construct($raw_response);

        $this->loadAccounts($this->getRawResponse()->accounts);
        $this->loadTransactions($this->getRawResponse()->transactions);

        $this->total_transactions = $this->getRawResponse()->total_transactions;
        $this->setRequestId($this->getRawResponse()->request_id);
        $this->setItem($this->getRawResponse()->item);
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
     * @return Transaction[]
     */
    public function getTransactions() {
        return $this->transactions;
    }

    /**
     * @return int
     */
    public function getTotalTransactions() {
        return $this->total_transactions;
    }
}