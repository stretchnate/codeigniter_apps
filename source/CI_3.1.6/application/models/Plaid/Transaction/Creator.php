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
            $category_name = $transaction->getCategory()[0];
            $category = $this->categoryExists($category_name);
            if ($category === false) {
                $category = $this->createCategory($transaction, $category_name);
            }

            if($category === false) {
                log_message('debug', 'Unable to create category for plaid transaction '.$transaction->getTransactionId());
            } elseif($transaction->getAmount() < 0) {
                $account = new \Budget_DataModel_AccountDM($category->getParentAccountId(), $this->user_id);
                $this->createDeposit($transaction, $account);
            } else {
                $this->createTransaction($transaction, $category);
            }
        }
    }

    /**
     * @param $category_name
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
     * @param \Budget_DataModel_AccountDM $account
     * @throws \Exception
     */
    private function createDeposit(TransactionResponse\Transaction $transaction, \Budget_DataModel_AccountDM $account) {
        $amount = abs($transaction->getAmount());

        //need to break this method up a bit
        $deposit = new \Deposit();
        $deposit->getValues()->setOwnerId((int)$this->user_id)
            ->setAccountId((int)$account->getAccountId())
            ->setSource($transaction->getName())
            ->setGross($amount)
            ->setNet($amount);
        $deposit->save();

        //need to create a deposit (new_funds) and get the id
        $transaction_dm = new \Budget_DataModel_TransactionDM();
        $transaction_dm->transactionStart();
        $transaction_dm->setOwnerId($this->user_id);
        $transaction_dm->setToAccount($account->getAccountId());
        $transaction_dm->setDepositId($deposit->getValues()->getId());
        $transaction_dm->setTransactionAmount($amount);
        $transaction_dm->setTransactionDate($transaction->getDate()->format('Y-m-d H:i:s'));
        $transaction_dm->setTransactionInfo($transaction->getName());
        if($transaction_dm->saveTransaction()) {
            $new_amount = add($account->getAccountAmount(), $amount, 2);
            $account->setAccountAmount($new_amount);
            dbo("save account = ".$account->saveAccount());
        }

        if(!$transaction_dm->transactionEnd()) {
            $db =& get_instance()->db;
            $error = $db->error();
            log_message('error', $error['message']);
            throw new \Exception("There was a problem processing your request.", EXCEPTION_CODE_VALIDATION);
        }
    }

    /**
     * @param TransactionResponse\Transaction $transaction
     * @param \Budget_DataModel_CategoryDM $category
     * @throws \Exception
     */
    private function createTransaction(TransactionResponse\Transaction $transaction, \Budget_DataModel_CategoryDM $category) {
        $transaction_dm = new \Budget_DataModel_TransactionDM();
        $transaction_dm->transactionStart();
        $transaction_dm->setOwnerId($this->user_id);
        $transaction_dm->setFromCategory($category->getCategoryId());
        $transaction_dm->setTransactionAmount($transaction->getAmount());
        $transaction_dm->setTransactionDate($transaction->getDate()->format('Y-m-d H:i:s'));
        $transaction_dm->setTransactionInfo($transaction->getName());
        if($transaction_dm->saveTransaction()) {
            $new_amount = subtract($category->getCurrentAmount(), $transaction->getAmount(), 2);
            $category->setCurrentAmount($new_amount);
            $category->saveCategory();
        }

        if(!$transaction_dm->transactionEnd()) {
            $db =& get_instance()->db;
            $error = $db->error();
            log_message('error', $error['message']);
            throw new \Exception("There was a problem processing your request.", EXCEPTION_CODE_VALIDATION);
        }
    }

    /**
     * @param TransactionResponse\Transaction $transaction
     * @param string $category_name
     * @throws \Exception
     * @return \Budget_DataModel_CategoryDM
     */
    private function createCategory($transaction, $category_name) {
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
            ->setCategoryName($category_name)
            ->setCurrentAmount(0)
            ->setDueDay($transaction->getDate()->format('Y-m-d'))// how can we tell if this appears more than once?
            ->setDueMonths([1,2,3,4,5,6,7,8,9,10,11,12])// can this be determined?
            ->setInterestBearing(0)
            ->setParentAccountId($connection->getValues()->getAccountId())
            ->setPriority(1);

        return $category_dm->saveCategory() ? $category_dm : false;
    }

}