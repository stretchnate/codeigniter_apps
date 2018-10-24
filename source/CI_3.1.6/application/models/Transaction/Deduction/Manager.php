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
     * @param Row      $transaction
     * @param Structure $transaction_updates
     * @return bool
     */
    public function modify(Row $transaction, Structure $transaction_updates, $user_id) {
        $category = new \Budget_DataModel_CategoryDM($transaction->getStructure()->getFromCategory(), $user_id);
        $diff = ($transaction->getStructure()->getTransactionAmount() > $transaction_updates->getTransactionAmount()) ?

        $transaction->getStructure()->setTransactionAmount($transaction_updates->getTransactionAmount());
        $transaction->getStructure()->setTransactionInfo($transaction_updates->getTransactionInfo());
        $transaction->getStructure()->setTransactionDate($transaction_updates->getTransactionDate());

        $amount = add($category->getCurrentAmount(), $diff);
        if(!$transaction_updates->getFromCategory()) {
            $transaction->getStructure()->setToCategory($transaction_updates->getToCategory());
            $transaction->getStructure()->setFromCategory(null);
            $amount = subtract($category->getCurrentAmount(), $diff);
        }

        $category->transactionStart();
        $transaction->saveTransaction();
        $category->saveCategory();
        $category->transactionEnd();

        $transaction->saveTransaction();
    }
}