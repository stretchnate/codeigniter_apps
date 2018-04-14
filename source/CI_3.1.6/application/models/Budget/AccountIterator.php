<?php

namespace Budget;

class AccountIterator extends \IteratorBase {

    protected $items;

    private $owner_id;

    public function __construct($owner_id, $where = null) {
        $this->key = 0;
        $this->owner_id = $owner_id;

        if($where) {
            $this->load($where);
        }
    }

    /**
     * @param array $where
     * @throws \Exception
     */
    public function load(array $where = []) {
        if(!$this->owner_id) {
            throw new \Exception('Access denied.', EXCEPTION_CODE_VALIDATION);
        }

        $where['owner_id'] = $this->owner_id;
        $this->db->select('account_id');
        $this->db->where($where);
        $query = $this->db->get(\Budget_DataModel_AccountDM::TABLE);

        if(!$query) {
            throw new \Exception('Unable to load account for '.$this->owner_id);
        }

        $this->items = [];
        foreach($query->result() as $row) {
            $this->items[] = new \Budget_DataModel_AccountDM($row->account_id);
        }
    }

    /**
     * return current Budget_DataModel_AccountDM
     *
     * @return \Budget_DataModel_AccountDM
     */
    public function current() {
        return $this->items[$this->key];
    }
}