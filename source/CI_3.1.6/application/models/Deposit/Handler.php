<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 4/28/2018
 * Time: 4:03 PM
 */

namespace Deposit;

use Funds\Distributor;

class Handler {

    private $user_id;

    public function __construct($user_id) {
        $this->user_id = $user_id;
    }

    /**
     * @param \Budget_DataModel_AccountDM $account_dm
     * @param mixed $amount
     * @param string $source
     * @param string $date
     * @param bool $distribute
     * @throws \Exception
     */
    public function addDeposit(\Budget_DataModel_AccountDM $account_dm, $amount, $source, $date, $distribute = true) {
        $deposit = $this->deposit($amount, $account_dm->getAccountId(), $source, $date);

        $transaction = new \Transaction();
        $transaction->transactionStart();

        $transaction->getStructure()->setToAccount($account_dm->getAccountId());
        $transaction->getStructure()->setDepositId($deposit->getValues()->getId());
        $transaction->getStructure()->setOwnerId($this->user_id);
        $transaction->getStructure()->setTransactionAmount((float)$amount);
        $transaction->getStructure()->setTransactionDate($date);
        $transaction->getStructure()->setTransactionInfo("Deposit ".$deposit->getValues()->getId()." into ".$account_dm->getAccountName());
        if($transaction->saveTransaction()) {
            $new_amount = add($account_dm->getAccountAmount(), $amount, 2);
            $account_dm->setAccountAmount($new_amount);
            $account_dm->saveAccount();
        }

        if($distribute === true) {
            $distributor = new Distributor($deposit, $this->user_id);
            $distributor->run();
        }

        if(!$transaction->transactionEnd()) {
            $db =& get_instance()->db;
            $error = $db->error();
            throw new \Exception($error['message'], EXCEPTION_CODE_ERROR);
        }
    }

    /**
     * @param $amount
     * @param $account_id
     * @param $source
     * @return \Deposit
     * @throws \Exception
     */
    private function deposit($amount, $account_id, $source, $date = null) {
        if(!$date) {
            $date = date("Y-m-d H:i:s");
        }

        $deposit = new \Deposit();
        $deposit->getValues()->setOwnerId((int)$this->user_id)
            ->setAccountId($account_id)
            ->setSource($source)
            ->setGross($amount)
            ->setDate($date)
            ->setNet($amount);
        $deposit->save();

        return $deposit;
    }
}