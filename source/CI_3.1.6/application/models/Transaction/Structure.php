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
     * @param $transaction_id
     */
    public function setTransactionId($transaction_id) {
        $this->transaction_id = $transaction_id;
    }

    /**
     * @return int
     */
    public function getToCategory() {
        return $this->to_category;
    }

    /**
     * @param $to_category
     */
    public function setToCategory($to_category) {
        $this->to_category = $to_category;
    }

    /**
     * @return int
     */
    public function getFromCategory() {
        return $this->from_category;
    }

    /**
     * @param $from_category
     */
    public function setFromCategory($from_category) {
        $this->from_category = $from_category;
    }

    /**
     * @return int
     */
    public function getToAccount() {
        return $this->to_account;
    }

    /**
     * @param $to_account
     */
    public function setToAccount($to_account) {
        $this->to_account = $to_account;
    }

    /**
     * @return int
     */
    public function getFromAccount() {
        return $this->from_account;
    }

    /**
     * @param $from_account
     */
    public function setFromAccount($from_account) {
        $this->from_account = $from_account;
    }

    /**
     * @return int
     */
    public function getDepositId() {
        return $this->deposit_id;
    }

    /**
     * @param $deposit_id
     */
    public function setDepositId($deposit_id) {
        $this->deposit_id = $deposit_id;
    }

    /**
     * @return int
     */
    public function getOwnerId() {
        return $this->owner_id;
    }

    /**
     * @param $owner_id
     */
    public function setOwnerId($owner_id) {
        $this->owner_id = $owner_id;
    }

    /**
     * @return float
     */
    public function getTransactionAmount() {
        return $this->transaction_amount;
    }

    /**
     * @param $transaction_amount
     */
    public function setTransactionAmount($transaction_amount) {
        $this->transaction_amount = $transaction_amount;
    }

    /**
     * @return string
     */
    public function getTransactionDate() {
        return $this->transaction_date;
    }

    /**
     * @param $transaction_date
     */
    public function setTransactionDate($transaction_date) {
        $pattern = "/[\/.-]/";

        if( preg_match($pattern, $transaction_date) ) {
            $transaction_date = strtotime($transaction_date);
        }

        $this->transaction_date = date("Y-m-d H:i:s", $transaction_date);
    }

    /**
     * @return string
     */
    public function getTransactionInfo() {
        return $this->transaction_info;
    }

    /**
     * @param $transaction_info
     */
    public function setTransactionInfo($transaction_info) {
        $this->transaction_info = $transaction_info;
    }

    /**
     * @return mixed
     */
    public function getInsertId() {
        return $this->insert_id;
    }

    /**
     * @return mixed
     */
    public function getToCategoryName() {
        return $this->to_category_name;
    }

    /**
     * @param $to_category_name
     */
    public function setToCategoryName($to_category_name) {
        $this->to_category_name = $to_category_name;
    }

    /**
     * @return mixed
     */
    public function getFromCategoryName() {
        return $this->from_category_name;
    }

    /**
     * @param $from_category_name
     */
    public function setFromCategoryName($from_category_name) {
        $this->from_category_name = $from_category_name;
    }

    /**
     * @return mixed
     */
    public function getToAccountName() {
        return $this->to_account_name;
    }

    /**
     * @param $to_account_name
     */
    public function setToAccountName($to_account_name) {
        $this->to_account_name = $to_account_name;
    }

    /**
     * @return mixed
     */
    public function getFromAccountName() {
        return $this->from_account_name;
    }

    /**
     * @param $from_account_name
     */
    public function setFromAccountName($from_account_name) {
        $this->from_account_name = $from_account_name;
    }
}