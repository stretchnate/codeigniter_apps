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
    private $access_token;

    /**
     * @var string
     */
    private $transactions_ready;

    /**
     * @var \DateTime
     */
    private $dt_added;

    public function toStdClass() {
        // TODO: Implement toStdClass() method.
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