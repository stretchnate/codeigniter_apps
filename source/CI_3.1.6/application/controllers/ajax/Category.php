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
class Category extends N8_Controller {

    public function fetchAll($account_id) {
        try {
            $dm = new Budget_DataModel_AccountDM($account_id, $this->session->user_id);
            $dm->loadCategories();
            $response = new stdClass();
            $categories = [];
            foreach($dm->getCategories() as $category_dm) {
                $c = new stdClass();
                $c->id = $category_dm->getCategoryId();
                $c->name = $category_dm->getCategoryName();
                $c->amount = $category_dm->getCurrentAmount();
                $c->amount_necessary = $category_dm->getAmountNecessary();
                $c->due_date = $category_dm->getNextDueDate()->getTimestamp();
                $c->parent_account_id = $category_dm->getParentAccountId();
                $c->active = $category_dm->getActive();
                $c->due_months = $category_dm->getDueMonths();
                $c->priority = $category_dm->getPriority();
                $c->plaid_category_id = $category_dm->getPlaidCategory();

                $categories[] = $c;
            }

            $response->categories = $categories;

            $this->JSONResponse($response);
        } catch(Exception $e) {
            log_message('error', $e);
            $this->JSONResponse(null, 500);
        }
    }

    /**
     * Fetch a single category - echo's a JSON response
     *
     * @param $id
     */
    public function get($id) {
        try {
            $dm = new Budget_DataModel_CategoryDM($id, $this->session->user_id);
            $category = new stdClass();
            $category->id = $dm->getCategoryId();
            $category->name = $dm->getCategoryName();
            $category->amount = $dm->getCurrentAmount();
            $category->amount_necessary = $dm->getAmountNecessary();
            $category->due_date = $dm->getNextDueDate()->getTimestamp();
            $category->parent_account_id = $dm->getParentAccountId();
            $category->active = $dm->getActive();
            $category->due_months = $dm->getDueMonths();
            $category->priority = $dm->getPriority();
            $category->plaid_category_id = $dm->getPlaidCategory();

            $this->JSONResponse($category);
        } catch(Exception $e) {
            log_message('error', $e);

            $this->JSONResponse(null, 500);
        }
    }

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