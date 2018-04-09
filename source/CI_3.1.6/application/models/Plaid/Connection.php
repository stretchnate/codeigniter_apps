<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/4/18
 * Time: 8:50 PM
 */

namespace Plaid;


use Plaid\Connection\Values;

/**
 * Class Item
 *
 * @package Plaid
 */
class Connection extends \CI_Model {

    /**
     *
     */
    const TABLE = 'plaid_connection';

    /**
     * @var Values
     */
    private $values;

    /**
     * Item constructor.
     *
     * @param null $values
     * @throws \Exception
     */
    public function __construct($values = null) {
        parent::__construct();
        $this->values = new Values();

        if($values) {
            $this->load($values);
        }
    }

    /**
     * @param Values $values
     * @throws \Exception
     */
    public function load($values) {
        $query = $this->db->get_where(self::TABLE, $values->toArray());

        if($query === false) {
            $error = $this->db->error();
            throw new \Exception($error['message'], EXCEPTION_CODE_ERROR);
        }

        if($query->num_rows() > 0) {
            $row = $query->row();
            $this->getValues()->setItemId($row->item_id);
            $this->getValues()->setAccountId((int)$row->account_id);
            $this->getValues()->setAccessToken($row->access_token);
            $this->getValues()->setTransactionsReady($row->transactions_ready);
            $this->getValues()->setDtAdded(new \DateTime($row->dt_added));
        }
    }

    /**
     * @return mixed
     */
    public function save() {
        if($this->rowExists()) {
            $result = $this->update();
        } else {
            $result = $this->insert();
        }

        return $result;
    }

    /**
     * @return mixed
     */
    private function insert() {
        $set = $this->set();
        if($this->getValues()->getItemId()) {
            $set->item_id = $this->getValues()->getItemId();
        }

        return $this->db->insert(self::TABLE, $set);
    }

    /**
     * @return mixed
     */
    private function update() {
        $this->db->where(['item_id' => $this->getValues()->getItemId()]);

        return $this->db->update(self::TABLE, $this->set());
    }

    /**
     * @return \stdClass
     */
    private function set() {
        $set = new \stdClass();
        $set->account_id = $this->getValues()->getAccountId();
        $set->access_token = $this->getValues()->getAccessToken();
        $set->transactions_ready = $this->getValues()->getTransactionsReady();
        $set->dt_added = $this->getValues()->getDtAdded()->format('Y-m-d H:i:s');

        return $set;
    }

    /**
     * @return bool
     */
    private function rowExists() {
        $result = false;
        if($this->getValues()->getItemId()) {
            $query = $this->db->get_where(self::TABLE, ['item_id' => $this->getValues()->getItemId()]);

            $result = ($query->num_rows() > 0);
        }

        return $result;
    }

    /**
     * @return Values
     */
    public function getValues() {
        return $this->values;
    }


}