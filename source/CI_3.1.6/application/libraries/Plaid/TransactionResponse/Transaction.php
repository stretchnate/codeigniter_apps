<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 7:16 PM
 */

namespace Plaid\TransactionResponse;


use Plaid\Location;

/**
 * Class Transaction
 *
 * @package Plaid\TransactionResponse
 */
class Transaction {

    /**
     * @var string
     */
    private $account_id;

    /**
     * @var double
     */
    private $amount;

    /**
     * @var string
     */
    private $account_owner;

    /**
     * @var array
     */
    private $category;

    /**
     * @var string
     */
    private $category_id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var Location
     */
    private $location;

    /**
     * @var string
     */
    private $name;

    /**
     * @var
     */
    private $payment_meta;

    /**
     * @var bool
     */
    private $pending;

    /**
     * @var string
     */
    private $pending_transaction_id;

    /**
     * @var string
     */
    private $transaction_id;

    /**
     * @var string
     */
    private $transaction_type;

    /**
     * @var \stdClass
     */
    private $raw_response;

    public function __construct(\stdClass $raw_response) {
        $this->raw_response = $raw_response;
        $this->setAccountId($this->raw_response->account_id);
        $this->setAccountOwner($this->raw_response->account_owner);
        $this->setAmount($this->raw_response->amount);
        $this->setCategory($this->raw_response->category);
        $this->setCategoryId($this->raw_response->category_id);
        $this->setDate(new DateTime($this->raw_response->date));
        $this->setLocation(new Location($this->raw_response->location));
        $this->setName($this->raw_response->name);
        $this->setPaymentMeta($this->raw_response->payment_meta);
        $this->setPending($this->raw_response->pending);
        $this->setPendingTransactionId($this->raw_response->pending_transaction_id);
        $this->setTransactionId($this->raw_response->transaction_id);
        $this->setTransactionType($this->raw_response->transaction_type);
    }

    /**
     * @return string
     */
    public function getAccountId() {
        return $this->account_id;
    }

    /**
     * @param string $account_id
     * @return Transaction
     */
    public function setAccountId($account_id) {
        $this->account_id = $account_id;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Transaction
     */
    public function setAmount($amount) {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return array
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param array $category
     * @return Transaction
     */
    public function setCategory($category) {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategoryId() {
        return $this->category_id;
    }

    /**
     * @param string $category_id
     * @return Transaction
     */
    public function setCategoryId($category_id) {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Transaction
     */
    public function setDate(\DateTime $date) {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Location
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @param Location $location
     * @return Transaction
     */
    public function setLocation(Location $location) {
        $this->location = $location;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Transaction
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentMeta() {
        return $this->payment_meta;
    }

    /**
     * @param mixed $payment_meta
     * @return Transaction
     */
    public function setPaymentMeta($payment_meta) {
        $this->payment_meta = $payment_meta;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPending() {
        return $this->pending;
    }

    /**
     * @param bool $pending
     * @return Transaction
     */
    public function setPending($pending) {
        $this->pending = $pending;

        return $this;
    }

    /**
     * @return string
     */
    public function getPendingTransactionId() {
        return $this->pending_transaction_id;
    }

    /**
     * @param string $pending_transaction_id
     * @return Transaction
     */
    public function setPendingTransactionId($pending_transaction_id) {
        $this->pending_transaction_id = $pending_transaction_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountOwner() {
        return $this->account_owner;
    }

    /**
     * @param string $account_owner
     * @return Transaction
     */
    public function setAccountOwner($account_owner) {
        $this->account_owner = $account_owner;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId() {
        return $this->transaction_id;
    }

    /**
     * @param string $transaction_id
     * @return Transaction
     */
    public function setTransactionId($transaction_id) {
        $this->transaction_id = $transaction_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionType() {
        return $this->transaction_type;
    }

    /**
     * @param string $transaction_type
     * @return Transaction
     */
    public function setTransactionType($transaction_type) {
        $this->transaction_type = $transaction_type;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }

}