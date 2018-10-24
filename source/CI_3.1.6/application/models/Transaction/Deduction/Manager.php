<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/14/2018
 * Time: 7:03 PM
 */

namespace Transaction\Deduction;
use Transaction\ManagerInterface;
use Transaction\Row;
use Transaction\Structure;

/**
 * Class Manger
 *
 * @package Transaction\Deduction
 */
class Manager implements ManagerInterface {

    /**
     * @param Row       $transaction
     * @param Structure $transaction_updates
     * @param           $user_id
     */
    public function modify(Row $transaction, Structure $transaction_updates, $user_id) {
        $category = new \Budget_DataModel_CategoryDM($transaction->getStructure()->getFromCategory(), $user_id);
        $category->setCurrentAmount($this->getCategoryAmount($category, $transaction, $transaction_updates));

        $transaction->getStructure()->setTransactionAmount($transaction_updates->getTransactionAmount());
        $transaction->getStructure()->setTransactionInfo($transaction_updates->getTransactionInfo());
        $transaction->getStructure()->setTransactionDate($transaction_updates->getTransactionDate());

        //if there is no from category in the update change this to an addition rather than deduction
        if(!$transaction_updates->getFromCategory()) {
            $transaction->getStructure()->setToCategory($transaction_updates->getToCategory());
            $transaction->getStructure()->setFromCategory(null);
        }

        $category->transactionStart();
        $transaction->saveTransaction();
        $category->saveCategory();
        $category->transactionEnd();

        $transaction->saveTransaction();
    }

    /**
     * @param \Budget_DataModel_CategoryDM $category
     * @param Row                          $transaction
     * @param Structure                    $transaction_updates
     * @return float
     */
    private function getCategoryAmount(\Budget_DataModel_CategoryDM $category, Row $transaction, Structure $transaction_updates) {
        $cat_amount = $category->getCurrentAmount();

        if($transaction->getStructure()->getTransactionAmount() > $transaction_updates->getTransactionAmount()) {
            //if the original transaction amount is greater than the update we need to add the difference into the category amount
            $diff = subtract($transaction->getStructure()->getTransactionAmount(), $transaction_updates->getTransactionAmount());
            $cat_amount = add($category->getCurrentAmount(), $diff);
        } elseif($transaction->getStructure()->getTransactionAmount() < $transaction_updates->getTransactionAmount()) {
            //if original transaction amount is less than the update we need to subtract the difference from the category amount
            $diff = subtract($transaction_updates->getTransactionAmount(), $transaction->getStructure()->getTransactionAmount());
            $cat_amount = subtract($category->getCurrentAmount(), $diff);
        }

        return $cat_amount;
    }
}