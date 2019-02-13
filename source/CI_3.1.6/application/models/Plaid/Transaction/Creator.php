<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/14/18
 * Time: 12:33 AM
 */

namespace Plaid\Transaction;


use Deposit\Handler;
use Plaid\Connection;
use Plaid\TransactionResponse;
use Traits\Distribute;
use Transaction\Row;

/**
 * Class Creator
 *
 * @package Plaid\Transaction
 */
class Creator {

    use Distribute;

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
        $account = $this->fetchAccount($transactions->getTransactions()[0]);
        foreach($transactions->getTransactions() as $transaction) {
            $category = $this->fetchCategory($transaction, $account);
            if (!$category instanceof \Budget_DataModel_CategoryDM) {
                $category_name = $transaction->getCategory()[0];
                $goal_amount = $this->calculateCategoryNeed($transactions->getTransactions(), $transaction);
                $category = $this->createCategory($transaction, $category_name, $category, $goal_amount);
            }
            if($category === false) {
                log_message('debug', 'Unable to create category for plaid transaction '.$transaction->getTransactionId());
            } elseif($transaction->getAmount() < 0) {
                $this->createDeposit($transaction, $account);
            } else {
                $this->createTransaction($transaction, $category);
            }
        }
    }

    /**
     * @param $plaid_category_id
     * @param $account_id
     * @return bool|\Budget_DataModel_CategoryDM
     * @throws \Exception
     */
    public function categoryExists($plaid_category_id, $account_id) {//need to use account id here too
        $category_dm = new \Budget_DataModel_CategoryDM();
        $category_dm->loadBy(['plaid_category' => $plaid_category_id, 'account_id' => $account_id, 'ownerId' => $this->user_id]);

        return ($category_dm->getCategoryId() > 0) ? $category_dm : false;
    }

    /**
     * @param $transaction
     * @return \Budget_DataModel_AccountDM
     * @throws \Exception
     */
    public function fetchAccount(TransactionResponse\Transaction $transaction) {
        $values = new Connection\Values();
        $values->setPlaidAccountId($transaction->getAccountId());
        $values->setActive(true);
        $connection = new Connection($values);
        $account = new \Budget_DataModel_AccountDM($connection->getValues()->getAccountId(), $this->user_id);

        return $account;
    }

    /**
     * @param $transaction
     * @param $account
     * @return bool|\Budget_DataModel_CategoryDM|float|int|string
     * @throws \Exception
     */
    public function fetchCategory(TransactionResponse\Transaction $transaction, \Budget_DataModel_AccountDM $account) {
        $plaid_category = substr($transaction->getCategoryId(), 0, 5);
        $category = $this->categoryExists($plaid_category, $account->getAccountId());
        if($category === false) {
            $plaid_category = substr($plaid_category, 0, 2) * 1000;
            $category = $this->categoryExists($plaid_category, $account->getAccountId());
        }

        return $category ? $category : $plaid_category;
    }

    /**
     * @param TransactionResponse\Transaction[] $transactions
     * @param TransactionResponse\Transaction $seed_transaction
     * @return mixed
     */
    private function calculateCategoryNeed(array $transactions, TransactionResponse\Transaction $seed_transaction) {
        $amount = 0;
        foreach($transactions as $transaction) {
            if($transaction->getCategory()[0] == $seed_transaction->getCategory()[0]
                && $transaction->getAmount() > 0) {

                $amount += $transaction->getAmount();
            }
        }

        return $amount;
    }

    /**
     * @param TransactionResponse\Transaction $transaction
     * @param \Budget_DataModel_AccountDM $account
     * @throws \Exception
     */
    private function createDeposit(TransactionResponse\Transaction $transaction, \Budget_DataModel_AccountDM $account) {
        $amount = abs($transaction->getAmount());

        $handler = new Handler($this->user_id);
        $handler->addDeposit($account, $amount, $transaction->getName(), $transaction->getDate(), false);

        $this->distribute($account);
    }

    /**
     * @param TransactionResponse\Transaction $transaction
     * @param \Budget_DataModel_CategoryDM $category
     * @throws \Exception
     */
    private function createTransaction(TransactionResponse\Transaction $transaction, \Budget_DataModel_CategoryDM $category) {
        $transaction_dm = new Row();
        $transaction_dm->getStructure()->setOwnerId($this->user_id);
        $transaction_dm->getStructure()->setFromCategory($category->getCategoryId());
        $transaction_dm->getStructure()->setTransactionAmount($transaction->getAmount());
        $transaction_dm->getStructure()->setTransactionDate($transaction->getDate()->format('Y-m-d H:i:s'));
        $transaction_dm->getStructure()->setTransactionInfo($transaction->getName());

        if(!$transaction_dm->saveTransaction()) {
            $db =& get_instance()->db;
            $error = $db->error();
            log_message('error', $error['message']);
            throw new \Exception("There was a problem processing your request.", EXCEPTION_CODE_VALIDATION);
        }

        $new_amount = subtract($category->getCurrentAmount(), $transaction->getAmount(), 2);
        $category->setCurrentAmount($new_amount);
        $category->saveCategory();
    }

    /**
     * @param TransactionResponse\Transaction $transaction
     * @param string $category_name
     * @param mixed $goal_amount
     * @throws \Exception
     * @return \Budget_DataModel_CategoryDM
     */
    private function createCategory($transaction, $category_name, $plaid_category, $goal_amount) {
        $cval = new Connection\Values();
        $cval->setPlaidAccountId($transaction->getAccountId());
        $connection = new Connection($cval);

        if(!$connection->getValues()->getAccountId()) {
            return false;
        }

        $category_dm = new \Budget_DataModel_CategoryDM();
        $category_dm->setOwnerId($this->user_id)
            ->setActive(1)
            ->setAmountNecessary((float)$goal_amount)
            ->setCategoryName($category_name)
            ->setCurrentAmount(0)
            ->setDueDay($transaction->getDate()->format('Y-m-d'))// how can we tell if this appears more than once?
            ->setDueMonths([1,2,3,4,5,6,7,8,9,10,11,12])// can this be determined?
            ->setInterestBearing(0)
            ->setParentAccountId($connection->getValues()->getAccountId())
            ->setPriority(1)
            ->setPlaidCategory($plaid_category);

        return $category_dm->saveCategory() ? $category_dm : false;
    }

}