<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 3/4/2019
 * Time: 3:30 PM
 */

/**
 * Class Category
 *
 * @author stretch
 */
class Category extends CI_Controller {

    /**
     * @param $category_id
     */
    public function getLastPaid($category_id) {
         // explicitly requiring the models speeds up the process
        require_once(APPPATH.'models/Budget/DataModel/CategoryDM.php');
        require_once(APPPATH.'models/TransactionIterator.php');
        try {
            $category = new Budget_DataModel_CategoryDM($category_id, $this->session->user_id);
            $structure = new \Transaction\Fields();
            $structure->setFromCategory($category->getCategoryId());
            $structure->setLimit(50);
            $structure->setOrderBy('transaction_id DESC');

            $transactions = new TransactionIterator();

            $transactions->load($structure);

            while($transactions->valid()) {
                if(!$transactions->current()->getStructure()->getToCategory() && !$transactions->current()->getStructure()->getToAccount()) {
                    die(json_encode([
                        'success' => true,
                        'data' => [
                            'transaction_id' => $transactions->current()->getStructure()->getTransactionId(),
                            'amount' => $transactions->current()->getStructure()->getTransactionAmount(),
                            'date' => $transactions->current()->getStructure()->getTransactionDate(),
                            'info' => $transactions->current()->getStructure()->getTransactionInfo()
                        ]
                    ]));
                }

                $transactions->next();
            }
        } catch(Exception $e) {
            log_message('error', $e);
        }

        die(json_encode(['success' => false]));
    }
}