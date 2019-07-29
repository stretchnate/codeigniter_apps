<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 7/28/2019
 * Time: 7:40 PM
 */

class Home extends N8_Controller {

    /**
     *
     */
    public function fetchAccountDisplay() {
        $this->auth->restrict();
        $home_model = new Budget_BusinessModel_Home();
        $home_model->loadAccounts($this->session->user_id);

        $accounts = [];
        foreach($home_model->getAccounts() as $account_dm) {
            $monthly_need = new monthlyNeed($account_dm);
            $account_dm->loadCategories();
//            $account_dm->orderCategoriesByDueFirst(date('Y-m-d'));

            $account = new stdClass();
            $account->id = $account_dm->getAccountId();
            $account->distributable_amount = $account_dm->getAccountAmount();
            $account->last_update = date('l F d, Y h:i:s a', strtotime($this->session->last_update));
            $account->last_transaction = $this->getLastTransaction();
            $account->monthly_need = number_format($monthly_need->getTotalNecessary(), 2, '.', ',');

            $account->categories = $this->setCategories($account_dm);

            $account->balance = $this->calculateAccountBalance($account->categories);

            $accounts[] = $account;
        }

//        echo json_encode($accounts);
        echo "<pre>".print_r($accounts, true)."</pre>";
    }

    /**
     * @param $categories
     * @return float|int
     */
    private function calculateAccountBalance($categories) {
        $balance = 0;
        foreach($categories as $category) {
            $balance = add($balance, $category->amount, 2);
        }

        return $balance;
    }

    /**
     * @param Budget_DataModel_AccountDM $account_dm
     * @return array
     */
    private function setCategories(Budget_DataModel_AccountDM $account_dm) {
        $categories = [];

        foreach($account_dm->getCategories() as $category_dm) {
            $category = new stdClass();
            $category->id = $category_dm->getCategoryId();
            $category->name = $category_dm->getCategoryName();
            $category->due_date = $category_dm->getNextDueDate()->format('m/d/Y');
            $category->goal = $category_dm->getAmountNecessary();
            $category->amount = $category_dm->getCurrentAmount();
            $category->last_paid = '';
            $category->difference = subtract($category_dm->getAmountNecessary(), $category_dm->getCurrentAmount(), 2);

            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * @return bool|String
     * @throws Exception
     */
    private function getLastTransaction() {
        $category_dm      = new Budget_DataModel_CategoryDM();
        $transactions     = $category_dm->fetchTransactions("desc");

        //because we fetched the transactions in descending order we want the first one
        if( is_array($transactions) && count($transactions) > 0 ) {
            return $this->UserFriendlyTransactionDetails($this->transactionDetails($transactions, 0));
        }

        return false;
    }
}