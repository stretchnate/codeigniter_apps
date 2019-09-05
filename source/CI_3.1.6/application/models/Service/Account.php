<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 9/4/2019
 * Time: 7:16 PM
 */

namespace Service;

/**
 * Class Account
 *
 * @package Service
 * @author  stret
 */
class Account {

    /**
     * @var Category
     */
    private $category_service_model;
    
    public function __construct() {
        $this->category_service_model = new Category();
    }

    /**
     * @param \Budget_DataModel_AccountDM $account_dm
     * @return stdClass
     */
    public function convertToJSON(\Budget_DataModel_AccountDM $account_dm) {
        $account = new \stdClass();
        $account->id = $account_dm->getAccountId();
        $account->name = $account_dm->getAccountName();
        $account->type = $account_dm->getAccountType();
        $account->active = $account_dm->getActive();
        $account->categories = [];
        foreach($account_dm->getCategories() as $category_dm) {
            $account->categories[] = $this->category_service_model->convertToJSON($category_dm);
        }

        return $account;
    }
}