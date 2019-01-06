<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 1/2/2019
 * Time: 9:25 PM
 */

class TransactionIterator extends IteratorBase {

    const TABLE = 'transactions';

    public function __construct(\Transaction\Structure $fields) {
        parent::__construct();
        if($fields) {
            $this->load($fields);
        }
    }

    /**
     * @param \Transaction\Structure $fields
     * @param null $order_by
     * @throws Exception
     */
    public function load(\Transaction\Structure $fields, $order_by = null) {
        if($fields) {
            $this->db->where($fields->whereString());
        }

        if($order_by) {
            $this->db->order_by($order_by);
        }

        $query = $this->db->get(self::TABLE);

        if(!$query) {
            throw new Exception($this->db->error()['message'], EXCEPTION_CODE_ERROR);
        }

        foreach($query->result() as $result) {
            $row = new \Transaction\Row();
            $row->getStructure()->setTransactionId($result->transaction_id)
                ->setDepositId($result->deposit_id)
                ->setOwnerId($result->owner_id)
                ->setTransactionDate($result->transaction_date)
                ->setTransactionInfo($result->transaction_info)
                ->setToCategory($result->to_category)
                ->setFromCategory($result->from_category)
                ->setTransactionAmount($result->transaction_amount)
                ->setToAccount($result->to_account)
                ->setFromAccount($result->from_account);

            $this->items[] = $row;
        }
    }

    /**
     * @return \Transaction\Row
     */
    public function current() {
        return($this->valid()) ? $this->items[$this->key] : null;
    }
}