<?php

/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 12/26/2018
 * Time: 10:07 AM
 */

/**
 * @return array
 * @throws Exception
 */
function getDistributableAmounts($user_id) {
    $fields = new \Deposit\Row\Fields();
    $fields->setOwnerId($user_id);
    $fields->setRemaining(0);
    $fields->setOperator('remaining', '>');
    $deposit = new \Deposit($fields, 'id DESC');
    $amount = [];
    while($deposit->valid()) {
        $acct_id = $deposit->current()->getFields()->getAccountId();
        if(isset($amount[$deposit->current()->getFields()->getAccountId()])) {
            $amount[$acct_id] += $deposit->current()->getFields()->getRemaining();
        } else {
            $amount[$acct_id] = $deposit->current()->getFields()->getRemaining();
        }

        $deposit->next();
    }

    return $amount;
}

/**
 * @param $user_id
 * @param $account_id
 * @return Deposit
 * @throws Exception
 */
function getActiveDeposits($user_id, $account_id) {
    $fields = new \Deposit\Row\Fields();
    $fields->setOwnerId($user_id);
    $fields->setAccountId($account_id);
    $fields->setRemaining(0);
    $fields->setOperator('remaining', '>');
    $deposit = new \Deposit($fields, 'id DESC');

    return $deposit;
}

