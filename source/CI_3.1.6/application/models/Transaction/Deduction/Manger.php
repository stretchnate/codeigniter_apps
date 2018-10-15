<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/14/2018
 * Time: 7:03 PM
 */

namespace Transaction\Deduction;
use Transaction\Structure;

/**
 * Class Manger
 *
 * @package Transaction\Deduction
 */
class Manger {

    /**
     * @param \Transaction $transaction
     * @param Structure $transaction_updates
     * @return bool
     */
    public function modify(\Transaction $transaction, Structure $transaction_updates) {
        if(!$transaction_updates->getFromCategory()) {
            $transaction->setToCategory($transaction_updates->getToCategory());
            $transaction->setFromCategory(null);
        }
        $transaction->setTransactionAmount($transaction_updates->getTransactionAmount());
        $transaction->setTransactionInfo($transaction_updates->getTransactionInfo());

        return $transaction->saveTransaction();
    }
}