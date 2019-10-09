<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 9/6/2019
 * Time: 7:11 PM
 */

class Transaction extends N8_Controller {

    /**
     * @param $transaction_id
     */
    public function get($transaction_id) {
        try {
            $transaction = new \Transaction\Row($transaction_id);

            $this->JSONResponse($transaction->getStructure()->toArray());
        } catch(Exception $e) {
            log_message('error', $e);

            $this->JSONResponse(null, 500);
        }
    }

    /**
     * @param        $id
     * @param        $type
     * @param string $id_type
     */
    public function fetchAll($id, $type, $id_type = 'category') {
        try {
            switch($id_type) {
                case 'account':
                    $iterator = new TransactionIterator($this->buildAccountWhere($id, $type));
                    break;
                default:
                    $iterator = new TransactionIterator($this->buildCategoryWhere($id, $type));
                    break;
            }

            $transactions = [];
            while($iterator->valid()) {
                $transactions[] = $iterator->current()->getStructure()->toArray();
                $iterator->next();
            }

            $this->JSONResponse($transactions);
        } catch(Exception $e) {
            log_message('error', $e);
            $this->JSONResponse(null, 500);
        }
    }

    /**
     * @param $id
     * @param $type
     * @return \Transaction\Fields
     */
    private function buildAccountWhere($id, $type) {
        $where = new \Transaction\Fields();
        switch($type) {
            case 'all':
                $where->setFromAccount($id);
            case 'credit':
                $where->setToAccount($id);
                break;
            case 'debit':
            default:
                $where->setFromAccount($id);
        }

        return $where;
    }

    /**
     * @param $id
     * @param $type
     * @return \Transaction\Fields
     */
    private function buildCategoryWhere($id, $type) {
        $where = new \Transaction\Fields();
        switch($type) {
            case 'all':
                $where->setFromCategory($id);
            case 'credit':
                $where->setToCategory($id);
                break;
            case 'debit':
            default:
                $where->setFromCategory($id);
        }

        return $where;
    }
}