<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/15/2018
 * Time: 9:51 PM
 */

namespace Transaction\Category\Deposit;

use Transaction\ManagerInterface;
use Transaction\Row;
use Transaction\Structure;

class Manager implements ManagerInterface {

    /**
     * @param Row       $transaction
     * @param Structure $transaction_updates
     * @param           $user_id
     * @throws \Exception
     */
    public function modify(Row $transaction, Structure $transaction_updates, $user_id) {
        $category = new \Budget_DataModel_CategoryDM($transaction->getStructure()->getToCategory(), $user_id);
        $account = new \Budget_DataModel_AccountDM($category->getParentAccountId(), $user_id);
        $this->updateAmounts($category, $account, $transaction, $transaction_updates);

        $transaction->getStructure()->setTransactionAmount($transaction_updates->getTransactionAmount());
        $transaction->getStructure()->setTransactionInfo($transaction_updates->getTransactionInfo());
        $transaction->getStructure()->setTransactionDate($transaction_updates->getTransactionDate());

        $category->transactionStart();
        $transaction->saveTransaction();
        $category->saveCategory();
        $account->saveAccount();
        $category->transactionEnd();

        $transaction->saveTransaction();
    }

    /**
     * @param \Budget_DataModel_CategoryDM $category
     * @param Row                          $transaction
     * @param Structure                    $transaction_updates
     * @return float
     */
    private function updateAmounts(\Budget_DataModel_CategoryDM $category, \Budget_DataModel_AccountDM $account, Row $transaction, Structure $transaction_updates) {
        $cat_amount = $category->getCurrentAmount();
        $account_amount = $account->getAccountAmount();

        if($transaction->getStructure()->getTransactionAmount() > $transaction_updates->getTransactionAmount()) {
            //if the original transaction amount is greater than the update we need to subtract the difference from the category amount
            $diff = subtract($transaction->getStructure()->getTransactionAmount(), $transaction_updates->getTransactionAmount());
            $cat_amount = subtract($category->getCurrentAmount(), $diff);
            $account_amount = add($account_amount, $diff);
        } elseif($transaction->getStructure()->getTransactionAmount() < $transaction_updates->getTransactionAmount()) {
            //if original transaction amount is less than the update we need to add the difference to the category amount
            $diff = subtract($transaction_updates->getTransactionAmount(), $transaction->getStructure()->getTransactionAmount());
            $cat_amount = add($category->getCurrentAmount(), $diff);
            $account_amount = subtract($account_amount, $diff);
        }

        $category->setCurrentAmount($cat_amount);
        $account->setAccountAmount($account_amount);
    }
}