<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/14/2018
 * Time: 8:53 PM
 */

namespace Transaction;

/**
 * Class Structure
 *
 * @package Transaction
 */
class Structure {

    /**
     * @var int
     */
    private $transaction_id;
    /**
     * @var int
     */
    private $to_category;
    /**
     * @var int
     */
    private $from_category;
    /**
     * @var int
     */
    private $to_account;
    /**
     * @var int
     */
    private $from_account;
    /**
     * @var int
     */
    private $deposit_id;
    /**
     * @var int
     */
    private $owner_id;
    /**
     * @var float
     */
    private $transaction_amount;
    /**
     * @var string
     */
    private $transaction_date;
    /**
     * @var string
     */
    private $transaction_info;

    /**
     * @return int
     */
    public function getTransactionId() {
        return $this->transaction_id;
    }

    /**
     * @param int $transaction_id
     * @return Structure
     */
    public function setTransactionId($transaction_id) {
        $this->transaction_id = $transaction_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getToCategory() {
        return $this->to_category;
    }

    /**
     * @param int $to_category
     * @return Structure
     */
    public function setToCategory($to_category) {
        $this->to_category = $to_category;
        return $this;
    }

    /**
     * @return int
     */
    public function getFromCategory() {
        return $this->from_category;
    }

    /**
     * @param int $from_category
     * @return Structure
     */
    public function setFromCategory($from_category) {
        $this->from_category = $from_category;
        return $this;
    }

    /**
     * @return int
     */
    public function getToAccount() {
        return $this->to_account;
    }

    /**
     * @param int $to_account
     * @return Structure
     */
    public function setToAccount($to_account) {
        $this->to_account = $to_account;
        return $this;
    }

    /**
     * @return int
     */
    public function getFromAccount() {
        return $this->from_account;
    }

    /**
     * @param int $from_account
     * @return Structure
     */
    public function setFromAccount($from_account) {
        $this->from_account = $from_account;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepositId() {
        return $this->deposit_id;
    }

    /**
     * @param int $deposit_id
     * @return Structure
     */
    public function setDepositId($deposit_id) {
        $this->deposit_id = $deposit_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getOwnerId() {
        return $this->owner_id;
    }

    /**
     * @param int $owner_id
     * @return Structure
     */
    public function setOwnerId($owner_id) {
        $this->owner_id = $owner_id;
        return $this;
    }

    /**
     * @return float
     */
    public function getTransactionAmount() {
        return $this->transaction_amount;
    }

    /**
     * @param float $transaction_amount
     * @return Structure
     */
    public function setTransactionAmount($transaction_amount) {
        $this->transaction_amount = $transaction_amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionDate() {
        return $this->transaction_date;
    }

    /**
     * @param string $transaction_date
     * @return Structure
     */
    public function setTransactionDate($transaction_date) {
        $this->transaction_date = $transaction_date;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionInfo() {
        return $this->transaction_info;
    }

    /**
     * @param string $transaction_info
     * @return Structure
     */
    public function setTransactionInfo($transaction_info) {
        $this->transaction_info = $transaction_info;
        return $this;
    }
}