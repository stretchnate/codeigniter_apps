<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 9/4/2019
 * Time: 7:23 PM
 */

namespace Account;

/**
 * Class Iterator
 *
 * @package Account
 * @author  stretch
 */
class Iterator extends \IteratorBase {

    /**
     * Iterator constructor.
     *
     * @param \stdClass|null $where
     * @throws \Exception
     */
    public function __construct($where = null) {
        parent::__construct();

        if($where) {
            $this->load($where);
        }
    }

    /**
     * @param $where
     * @throws \Exception
     */
    public function load(array $where) {
        if(!isset($where['owner_id'])) {
            throw new \RuntimeException('no user id provided.');
        }

        $this->db->select('account_id');
        $query = $this->db->get_where('accounts', $where);

        if(!$query) {
            throw new \Exception($this->db->error()['message']);
        }

        foreach($query->result() as $row) {
            $account_dm = new \Budget_DataModel_AccountDM($row->account_id, $where['owner_id']);
            $account_dm->loadCategories();

            $this->items[] = $account_dm;
        }
    }

    /**
     * @return \Budget_DataModel_AccountDM|null
     */
    public function current() {
        return isset($this->items[$this->key]) ? $this->items[$this->key] : null;
    }
}