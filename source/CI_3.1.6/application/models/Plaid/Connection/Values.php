<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/4/18
 * Time: 9:33 PM
 */

namespace Plaid\Connection;


class Values extends \Validation implements \ValueInterface {

    /**
     * @var int
     */
    private $item_id;

    /**
     * @var int
     */
    private $account_id;

    /**
     * @var string
     */
    private $plaid_account_id;

    /**
     * @var string
     */
    private $access_token;

    /**
     * @var string
     */
    private $transactions_ready;

    private $transactions_updated;

    /**
     * @var \DateTime
     */
    private $dt_added;

    /**
     * @return array
     */
    public function toArray() {
        $where = [];
        if($this->getItemId()) {
            $where['item_id'] = $this->getItemId();
        }
        if($this->getAccountId()) {
            $where['account_id'] = $this->getAccountId();
        }
        if($this->getPlaidAccountId()) {
            $where['plaid_account_id'] = $this->getPlaidAccountId();
        }
        if($this->getAccessToken()) {
            $where['access_token'] = $this->getAccessToken();
        }
        if($this->getTransactionsReady()) {
            $where['transactions_ready'] = $this->getTransactionsReady();
        }
        if($this->getTransactionsUpdated()) {
            $where['transactions_updated'] = $this->getTransactionsUpdated()->format('Y-m-d H:i:s');
        }
        if($this->getDtAdded()) {
            $where['dt_added'] = $this->getDtAdded()->format('Y-m-d H:i:s');
        }

        return $where;
    }

    public function __construct() {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getItemId() {
        return $this->item_id;
    }

    /**
     * @param string $item_id
     * @return Values
     * @throws \Exception
     */
    public function setItemId($item_id) {
        if(isset($this->item_id)) {
            throw new \Exception('Overwriting id is not allowed.', EXCEPTION_CODE_ERROR);
        }

        $this->item_id = $item_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->account_id;
    }

    /**
     * @param int $account_id
     * @return Values
     */
    public function setAccountId($account_id) {
        $this->account_id = $this->simple_validation->isInt($account_id);

        return $this;
    }

    /**
     * @return string
     */
    public function getPlaidAccountId() {
        return $this->plaid_account_id;
    }

    /**
     * @param string $plaid_account_id
     * @return Values
     */
    public function setPlaidAccountId(string $plaid_account_id) {
        $this->plaid_account_id = $plaid_account_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken() {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     * @return Values
     */
    public function setAccessToken($access_token) {
        $this->access_token = $this->simple_validation->isString($access_token);

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionsReady() {
        return $this->transactions_ready;
    }

    /**
     * @param string $transactions_ready
     * @return Values
     */
    public function setTransactionsReady($transactions_ready) {
        $this->transactions_ready = $this->simple_validation->isString($transactions_ready);

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getTransactionsUpdated() {
        return isset($this->transactions_updated) ? clone $this->transactions_updated : null;
    }

    /**
     * @param \DateTime $transactions_updated
     * @return Values
     */
    public function setTransactionsUpdated(\DateTime $transactions_updated) {
        $this->transactions_updated = $transactions_updated;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDtAdded() {
        return isset($this->dt_added) ? clone $this->dt_added : null;
    }

    /**
     * @param \DateTime $dt_added
     * @return Values
     */
    public function setDtAdded(\DateTime $dt_added) {
        $this->dt_added = $dt_added;

        return $this;
    }
}