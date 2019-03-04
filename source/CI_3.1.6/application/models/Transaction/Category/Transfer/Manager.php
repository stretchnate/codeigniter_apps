<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/15/2018
 * Time: 9:47 PM
 */

namespace Transaction\Category\Transfer;

use Transaction\ManagerInterface;
use Transaction\Row;
use Transaction\Fields;

/**
 * Class Manager
 *
 * @package Transaction\Category\Transfer
 */
class Manager implements ManagerInterface {

    /**
     * update the details of a category transfer
     *
     * @param Row    $transaction
     * @param Fields $transaction_updates
     */
    public function modify(Row $transaction, Fields $transaction_updates, $user_id) {
        //need to make sure from category can handle the new amount
        $from_category = new \Budget_DataModel_CategoryDM($transaction->getStructure()->getFromCategory(), $user_id);
        $to_category = new \Budget_DataModel_CategoryDM($transaction->getStructure()->getToCategory(), $user_id);
        if($transaction->getStructure()->getTransactionAmount() < $transaction_updates->getTransactionAmount()) {
            $diff = subtract($transaction_updates->getTransactionAmount(), $transaction->getStructure()->getTransactionAmount(), 2);
            if($from_category->getCurrentAmount() < $diff) {
                $diff = $from_category->getCurrentAmount();
                $transaction_updates->setTransactionAmount(add($transaction->getStructure()->getTransactionAmount(), $diff, 2));
            }

            $to_category_amount = add($to_category->getCurrentAmount(), $diff, 2);
            $from_category_amount = subtract($from_category->getCurrentAmount(), $diff, 2);
        } else {
            $diff = subtract($transaction->getStructure()->getTransactionAmount(), $transaction_updates->getTransactionAmount(), 2);
            if($to_category->getCurrentAmount() < $diff) {
                $diff = $to_category->getCurrentAmount();
                $transaction_updates->setTransactionAmount(add($transaction->getStructure()->getTransactionAmount(), $diff, 2));
            }

            $to_category_amount = subtract($to_category->getCurrentAmount(), $diff, 2);
            $from_category_amount = add($from_category->getCurrentAmount(), $diff, 2);
        }

        $to_category->setCurrentAmount($to_category_amount);
        $from_category->setCurrentAmount($from_category_amount);
        $transaction->getStructure()->setTransactionAmount($transaction_updates->getTransactionAmount());
        $transaction->getStructure()->setTransactionDate($transaction_updates->getTransactionDate());
        $transaction->getStructure()->setTransactionInfo($transaction_updates->getTransactionInfo());

        $from_category->transactionStart();
        $from_category->saveCategory();
        $to_category->saveCategory();
        $transaction->saveTransaction();
        $from_category->transactionEnd();
    }
}