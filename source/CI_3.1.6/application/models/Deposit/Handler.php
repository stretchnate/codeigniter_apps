<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 4/28/2018
 * Time: 4:03 PM
 */

namespace Deposit;

use Transaction\Row;

/**
 * Class Handler
 *
 * @package Deposit
 * @author  stret
 */
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
    public function addDeposit(\Budget_DataModel_AccountDM $account_dm, $amount, $source, \DateTime $date, $manual_distribution) {
        $this->db->trans_begin();

        $deposit = $this->deposit($amount, $account_dm->getAccountId(), $source, $date, $manual_distribution);
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
     * @param \Budget_DataModel_AccountDM  $parent_account
     * @param \Budget_DataModel_CategoryDM $category
     * @param \Deposit\Row                 $deposit
     * @param                              $requested_amount
     * @param                              $date
     * @throws \Exception
     */
    public function distributeFunds(\Budget_DataModel_AccountDM $parent_account, \Budget_DataModel_CategoryDM $category, \Deposit\Row $deposit, $requested_amount, $date) {
        $this->db->trans_start();
        $deposit->getFields()->setRemaining(subtract($deposit->getFields()->getRemaining(), $requested_amount, 2));

        $category->setCurrentAmount(add($category->getCurrentAmount(), $requested_amount, 2));

        $deposit->save();
        $category->saveCategory();

        if( $category->isErrors() === false ) {
            $transaction = new \Transaction\Row();
            $transaction->getStructure()->setToCategory($category->getCategoryId());
            $transaction->getStructure()->setFromAccount($parent_account->getAccountId());
            $transaction->getStructure()->setDepositId($deposit->getFields()->getId());
            $transaction->getStructure()->setOwnerId($this->session->userdata("user_id"));
            $transaction->getStructure()->setTransactionAmount($requested_amount);
            $transaction->getStructure()->setTransactionDate($date);
            $transaction->getStructure()->setTransactionInfo("Funds distributed from ".$parent_account->getAccountName()." account to ".$category->getCategoryName());
            $transaction->saveTransaction();
        }

        $this->db->trans_complete();
    }

    /**
     * @param $amount
     * @param $account_id
     * @param $source
     * @param \DateTime $date
     * @param bool $manual_distribution
     * @return \Deposit\Row
     * @throws \Exception
     */
    private function deposit($amount, $account_id, $source, \DateTime $date, $manual_distribution = false) {
        $deposit = new \Deposit\Row();
        $deposit->getFields()->setOwnerId((int)$this->user_id)
            ->setAccountId($account_id)
            ->setSource($source)
            ->setGross($amount)
            ->setDate($date)
            ->setNet($amount)
            ->setRemaining($amount)
            ->setManualDistribution($manual_distribution);
        $deposit->save();

        return $deposit;
    }
}