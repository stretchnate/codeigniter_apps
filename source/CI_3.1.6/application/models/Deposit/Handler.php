<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 4/28/2018
 * Time: 4:03 PM
 */

namespace Deposit;

use Funds\Distributor;
use Transaction\Row;

class Handler extends \CI_Model {

    private $user_id;

    public function __construct($user_id) {
        $this->user_id = $user_id;
    }

    /**
     * @param \Budget_DataModel_AccountDM $account_dm
     * @param mixed $amount
     * @param string $source
     * @param \DateTime $date
     * @return \Deposit\Row
     * @throws \Exception
     */
    public function addDeposit(\Budget_DataModel_AccountDM $account_dm, $amount, $source, \DateTime $date) {
        $this->db->trans_begin();

        $deposit = $this->deposit($amount, $account_dm->getAccountId(), $source, $date);
        $transaction = new Row();

        $transaction->getStructure()->setToAccount($account_dm->getAccountId());
        $transaction->getStructure()->setDepositId($deposit->getFields()->getId());
        $transaction->getStructure()->setOwnerId($this->user_id);
        $transaction->getStructure()->setTransactionAmount((float)$amount);
        $transaction->getStructure()->setTransactionDate($date->format('Y-m-d H:i:s'));
        $transaction->getStructure()->setTransactionInfo("Deposit ".$deposit->getFields()->getId()." into ".$account_dm->getAccountName());
        $transaction->saveTransaction();

        if(!$this->db->trans_status()) {
            $this->db->trans_rollback();
            throw new \Exception($this->db->error()['message'], EXCEPTION_CODE_ERROR);
        }
        $this->db->trans_commit();

        return $deposit;
    }

    /**
     * @param $amount
     * @param $account_id
     * @param $source
     * @param \DateTime $date
     * @return \Deposit\Row
     * @throws \Exception
     */
    private function deposit($amount, $account_id, $source, \DateTime $date) {
        $deposit = new \Deposit\Row();
        $deposit->getFields()->setOwnerId((int)$this->user_id)
            ->setAccountId($account_id)
            ->setSource($source)
            ->setGross($amount)
            ->setDate($date)
            ->setNet($amount)
            ->setRemaining($amount);
        $deposit->save();

        return $deposit;
    }
}