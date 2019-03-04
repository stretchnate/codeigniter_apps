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
use Transaction\Fields;

/**
 * Class Manger
 *
 * @package Transaction\Deduction
 */
class Manager implements ManagerInterface {

    /**
     * @param Row       $transaction
     * @param Fields    $transaction_updates
     * @param           $user_id
     * @throws \Exception
     */
    public function modify(Row $transaction, Fields $transaction_updates, $user_id) {
        $category = new \Budget_DataModel_CategoryDM($transaction->getStructure()->getFromCategory(), $user_id);
        $this->updateAmounts($category, $transaction, $transaction_updates);

        $transaction->getStructure()->setTransactionAmount($transaction_updates->getTransactionAmount());
        $transaction->getStructure()->setTransactionInfo($transaction_updates->getTransactionInfo());
        $transaction->getStructure()->setTransactionDate($transaction_updates->getTransactionDate());

        $category->transactionStart();
        $transaction->saveTransaction();
        $category->saveCategory();
        $category->transactionEnd();

        $transaction->saveTransaction();
    }

    /**
     * @param \Budget_DataModel_CategoryDM $category
     * @param Row                          $transaction
     * @param Fields                       $transaction_updates
     */
    private function updateAmounts(\Budget_DataModel_CategoryDM $category, Row $transaction, Fields $transaction_updates) {
        $cat_amount = $category->getCurrentAmount();

        if($transaction->getStructure()->getTransactionAmount() > $transaction_updates->getTransactionAmount()) {
            //if the original transaction amount is greater than the update we need to add the difference into the category amount
            $diff = subtract($transaction->getStructure()->getTransactionAmount(), $transaction_updates->getTransactionAmount(), 2);
            $cat_amount = add($category->getCurrentAmount(), $diff, 2);
        } elseif($transaction->getStructure()->getTransactionAmount() < $transaction_updates->getTransactionAmount()) {
            //if original transaction amount is less than the update we need to subtract the difference from the category amount
            $diff = subtract($transaction_updates->getTransactionAmount(), $transaction->getStructure()->getTransactionAmount(), 2);
            $cat_amount = subtract($category->getCurrentAmount(), $diff, 2);
        }

        $category->setCurrentAmount($cat_amount);
    }
}