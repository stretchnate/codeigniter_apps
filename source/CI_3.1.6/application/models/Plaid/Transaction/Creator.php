<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/14/18
 * Time: 12:33 AM
 */

namespace Plaid\Transaction;


use Plaid\Connection;
use Plaid\TransactionResponse;

/**
 * Class Creator
 *
 * @package Plaid\Transaction
 */
class Creator {

    /**
     * @var int
     */
    private $user_id;

    /**
     * Creator constructor.
     *
     * @param $user_id
     */
    public function __construct($user_id) {
        $this->user_id = $user_id;
    }

    /**
     * @param TransactionResponse $transactions
     * @throws \Exception
     */
    public function convertTransactionsToCategories(TransactionResponse $transactions) {
        foreach($transactions->getTransactions() as $transaction) {
            $category = $this->categoryExists($transaction->getCategory()[0]);
            if ($category === false) {
                $category = $this->createCategory($transaction);
            }

            if($category === false) {
                log_message('debug', 'Unable to create category for plaid transaction '.$transaction->getTransactionId());
            } else {
                $this->createTransaction($transaction, $category);
            }
        }
    }

    /**
     * @param $plaid_category_id
     * @return bool|\Budget_DataModel_CategoryDM
     * @throws \Exception
     */
    public function categoryExists($category_name) {//need to use account id here too
        $category_dm = new \Budget_DataModel_CategoryDM();
        $category_dm->loadBy(['bookName' => $category_name, 'ownerId' => $this->user_id]);

        return ($category_dm->getCategoryId() > 0) ? $category_dm : false;
    }

    /**
     * @param TransactionResponse\Transaction $transaction
     * @param \Budget_DataModel_CategoryDM $category
     */
    private function createTransaction(TransactionResponse\Transaction $transaction, \Budget_DataModel_CategoryDM $category) {
        $transaction_dm = new \Budget_DataModel_TransactionDM();
        $transaction_dm->transactionStart();
        $transaction_dm->setOwnerId($this->user_id);
        $transaction_dm->setFromCategory($category->getCategoryId());
        $transaction_dm->setTransactionAmount($transaction->getAmount());
        $transaction_dm->setTransactionDate($transaction->getDate()->format('Y-m-d H:i:s'));
        if($transaction_dm->saveTransaction()) {
            $new_amount = subtract($category->getCurrentAmount(), $transaction->getAmount(), 2);
            $category->setCurrentAmount($new_amount);
            $category->saveCategory();
        }
        $transaction_dm->transactionEnd();

    }

    /**
     * @param TransactionResponse\Transaction $transaction
     * @throws \Exception
     * @return \Budget_DataModel_CategoryDM
     */
    private function createCategory($transaction) {
        $cval = new Connection\Values();
        $cval->setPlaidAccountId($transaction->getAccountId());
        $connection = new Connection($cval);

        if(!$connection->getValues()->getAccountId()) {
            return false;
        }

        $category_dm = new \Budget_DataModel_CategoryDM();
        $category_dm->setOwnerId($this->user_id)
            ->setActive(1)
            ->setAmountNecessary(0)// can this be calcualted?
            ->setCategoryName($transaction->getCategory()[0])
            ->setCurrentAmount(0)
            ->setDueDay($transaction->getDate()->format('Y-m-d'))// how can we tell if this appears more than once?
            ->setDueMonths([1,2,3,4,5,6,7,8,9,10,11,12])// can this be determined?
            ->setInterestBearing(0)
            ->setParentAccountId($connection->getValues()->getAccountId())
            ->setPlaidCategoryId($transaction->getCategoryId())
            ->setPriority(1);

        return $category_dm->saveCategory() ? $category_dm : false;
    }

}