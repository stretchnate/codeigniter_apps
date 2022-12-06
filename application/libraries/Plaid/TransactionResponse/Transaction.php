<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 7:16 PM
 */

namespace Plaid\TransactionResponse;


use Plaid\Location;
use Plaid\Plaid;

/**
 * Class Transaction
 *
 * @package Plaid\TransactionResponse
 */
class Transaction extends Plaid {

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

    public function __construct(\stdClass $raw_response) {
        parent::__construct($raw_response);
        $this->account_id = $this->getRawResponse()->account_id;
        $this->account_owner = $this->getRawResponse()->account_owner;
        $this->amount = $this->getRawResponse()->amount;
        $this->category = $this->getRawResponse()->category;
        $this->category_id = $this->getRawResponse()->category_id;
        $this->date = new \DateTime($this->getRawResponse()->date);
        $this->location = new Location($this->getRawResponse()->location);
        $this->name = $this->getRawResponse()->name;
        $this->payment_meta = $this->getRawResponse()->payment_meta;
        $this->pending = $this->getRawResponse()->pending;
        $this->pending_transaction_id = $this->getRawResponse()->pending_transaction_id;
        $this->transaction_id = $this->getRawResponse()->transaction_id;
        $this->transaction_type = $this->getRawResponse()->transaction_type;
    }

    /**
     * @return string
     */
    public function getAccountId() {
        return $this->account_id;
    }

    /**
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getAccountOwner() {
        return $this->account_owner;
    }

    /**
     * @return array
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getCategoryId() {
        return $this->category_id;
    }

    /**
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @return Location
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPaymentMeta() {
        return $this->payment_meta;
    }

    /**
     * @return bool
     */
    public function isPending() {
        return $this->pending;
    }

    /**
     * @return string
     */
    public function getPendingTransactionId() {
        return $this->pending_transaction_id;
    }

    /**
     * @return string
     */
    public function getTransactionId() {
        return $this->transaction_id;
    }

    /**
     * @return string
     */
    public function getTransactionType() {
        return $this->transaction_type;
    }
}