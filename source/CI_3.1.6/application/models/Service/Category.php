<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 9/4/2019
 * Time: 7:03 PM
 */

namespace Service;

/**
 * Class Category
 *
 * @package Service
 * @author  stret
 */
class Category {

    /**
     * @param \Budget_DataModel_CategoryDM $category_dm
     * @return stdClass
     */
    public function convertToJSON(\Budget_DataModel_CategoryDM $category_dm) {
        $category = new \stdClass();
        $category->id = $category_dm->getCategoryId();
        $category->name = $category_dm->getCategoryName();
        $category->amount = $category_dm->getCurrentAmount();
        $category->amount_necessary = $category_dm->getAmountNecessary();
        $category->due_date = $category_dm->getNextDueDate()->getTimestamp();
        $category->parent_account_id = $category_dm->getParentAccountId();
        $category->active = $category_dm->getActive();
        $category->due_months = $category_dm->getDueMonths();
        $category->priority = $category_dm->getPriority();
        $category->plaid_category_id = $category_dm->getPlaidCategory();

        return $category;
    }
}