<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/15/2018
 * Time: 9:50 PM
 */

namespace Transaction\Refund;

use Transaction\ManagerInterface;
use Transaction\Row;
use Transaction\Structure;

/**
 * Class Manager
 *
 * @package Transaction\Refund
 */
class Manager implements ManagerInterface {

    /**
     * @param Row       $transaction
     * @param Structure $transaction_updates
     * @param           $user_id
     * @throws \Exception
     */
    public function modify(Row $transaction, Structure $transaction_updates, $user_id) {
        $category = new \Budget_DataModel_CategoryDM($transaction->getStructure()->getFromCategory(), $user_id);
        $category_amount = $category->getCurrentAmount();

        if($transaction->getStructure()->getTransactionAmount() > $transaction_updates->getTransactionAmount()) {
            $diff = subtract($transaction->getStructure()->getTransactionAmount(), $transaction_updates->getTransactionAmount());
            $category_amount = subtract($category->getCurrentAmount(), $diff);
        } elseif($transaction->getStructure()->getTransactionAmount() < $transaction_updates->getTransactionAmount()) {
            $diff = subtract($transaction_updates->getTransactionAmount(), $transaction->getStructure()->getTransactionAmount());
            $category_amount = add($category->getCurrentAmount(), $diff);
        }
        $transaction->getStructure()->setTransactionDate($transaction_updates->getTransactionDate());
        $transaction->getStructure()->setTransactionInfo($transaction_updates->getTransactionInfo());
        $transaction->getStructure()->setTransactionAmount($transaction_updates->getTransactionAmount());
        $category->setCurrentAmount($category_amount);

        $category->transactionStart();
        $transaction->saveTransaction();
        $category->saveCategory();
        $category->transactionEnd();
    }
}