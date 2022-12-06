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
class Fields extends \Structure {

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

    public function toArray() {
        $result = [];
        if($this->transaction_id) {
            $result['transaction_id'] = $this->transaction_id;
        }
        if($this->owner_id) {
            $result['owner_id'] = $this->owner_id;
        }
        if($this->to_category) {
            $result['to_category'] = $this->to_category;
        }
        if($this->from_category) {
            $result['from_category'] = $this->from_category;
        }
        if($this->to_account) {
            $result['to_account'] = $this->to_account;
        }
        if(isset($this->from_account)) {
            $result['from_account'] = $this->from_account;
        }
        if(isset($this->deposit_id)) {
            $result['deposit_id'] = $this->deposit_id;
        }
        if(isset($this->transaction_amount)) {
            $result['transaction_amount'] = $this->transaction_amount;
        }
        if(isset($this->transaction_date)) {
            $result['transaction_date'] = $this->transaction_date;
        }
        if($this->transaction_info) {
            $result['transaction_info'] = $this->transaction_info;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function whereString() {
        $where = [];

        foreach($this->toArray() as $field => $value) {
            if(array_key_exists($field, $this->operators)) {
                $where[] = operator($this->operators[$field], $field, $value);
            } else {
                $where[] = operator('=', $field, $value);
            }
        }

        return '(' . implode(') AND (', $where) . ')';
    }

    /**
     * @return int
     */
    public function getTransactionId() {
        return $this->transaction_id;
    }

    /**
     * @param int $transaction_id
     * @return Fields
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
     * @return Fields
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
     * @return Fields
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
     * @return Fields
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
     * @return Fields
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
     * @return Fields
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
     * @return Fields
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
     * @return Fields
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
     * @return Fields
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
     * @return Fields
     */
    public function setTransactionInfo($transaction_info) {
        $this->transaction_info = $transaction_info;
        return $this;
    }

    /**
     * set operator for where string
     * @param $field
     * @param $operator
     */
    public function setOperator($field, $operator) {
        $this->operators[$field] = $operator;
    }
}