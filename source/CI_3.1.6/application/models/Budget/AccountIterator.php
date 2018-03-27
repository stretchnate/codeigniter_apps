<?php

namespace Budget;

class AccountIterator extends IteratorBase {

    protected $items;

    public function __construct($where = null) {
        $this->key = 0;
        if($where) {
            $this->load($where);
        }
    }

    /**
     * @param \stdClass $where
     * @return void
     */
    public function load(stdClass $where) {
        if(!$where->owner_id || $where->owner_id != $this->session->userdata("user_id")) {
            $where->owner_id = $this->session->userdata("user_id");
        }

        $this->db->select('account_id');
        $this->db->where($where);
        $query = $this->db->get(Budget_DataModel_AccountDM::TABLE);

        if(!$query) {
            throw new Exception('Unable to load account for '.$where->owner_id);
        }

        $this->items = [];
        foreach($query->result() as $row) {
            $this->items[] = new Budget_DataModel_AccountDM($row->account_id);
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