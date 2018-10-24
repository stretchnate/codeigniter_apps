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

        $transaction->getStructure()->setTransactionAmount($transaction_updates->getTransactionAmount());
        $transaction->getStructure()->setTransactionInfo($transaction_updates->getTransactionInfo());
        $transaction->getStructure()->setTransactionAmount($transaction_updates->getTransactionAmount());
        if($transaction_updates->getFromCategory()) {
            $transaction->getStructure()->setFromCategory($transaction_updates->getFromCategory());
            $transaction->getStructure()->setToCategory(null);
            $category->setCurrentAmount(subtract($category->getCurrentAmount(), $transaction->getStructure()->getTransactionAmount()));
        } else {
            $category->setCurrentAmount(add($category->getCurrentAmount(), $transaction->getStructure()->getTransactionAmount()));
        }

        $category->transactionStart();
        $transaction->saveTransaction();
        $category->saveCategory();
        $category->transactionEnd();
    }
}