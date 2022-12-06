<?php

namespace Plaid;

use Plaid\Transaction\Values;

class Transaction extends \CI_Model {

    const TABLE = 'plaid_transaction';

    /**
     * @var Values
     */
    private $values;

    public function __construct($values = null) {
        parent::__construct();
        $this->values = new Values();

        if($values) {
            $this->load($values);
        }
    }

    /**
     * load the db row
     *
     * @param Values $values
     * @return void
     * @throws \Exception
     */
    public function load(Values $values) {
        $query = $this->db->get_where(self::TABLE, $values->toArray());

        if(!$query) {
            $error = $this->db->error();
            throw new Exception($error['message']);
        }

        $this->getValues()->setId((int)$query->row()->id);
        $this->getValues()->setRequestId($query->row()->request_id);
        $this->getValues()->setData($query->row()->data);
        $this->getValues()->setAdded(new \DateTime($query->row()->added));
    }

    /**
     * save the data to the db
     *
     * @return bool
     * @throws \Exception
     */
    public function save() {
        if($this->rowExists()) {
            return $this->update();
        }
        return $this->insert();
    }

    /**
     * update an existing row
     *
     * @return bool
     */
    private function update() {
        $set = $this->buildSet();
        unset($set['id']);
        $this->db->where(['id' => $this->getValues()->getId()]);

        return $this->db->update(self::TABLE, $set);
    }

    /**
     * insert a new row
     *
     * @return bool
     */
    private function insert() {
        $values = $this->buildSet();

        return $this->db->insert(self::TABLE, $values);
    }

    /**
     * build set/values array
     *
     * @return array
     */
    private function buildSet() {
        return $this->getValues()->toArray();
    }

    /**
     * check if row (primary key) already exists
     *
     * @return bool
     * @throws \Exception
     */
    private function rowExists() {
        $query = $this->db->get_where(self::TABLE, ['id' => $this->getValues()->getId()]);

        if(!$query) {
            $error = $this->db->error();
            throw new \Exception($error['message']);
        }

        return ($query->num_rows() > 0);
    }

    /**
     * @return Values
     */
    public function getValues() {
        return $this->values;
    }
}