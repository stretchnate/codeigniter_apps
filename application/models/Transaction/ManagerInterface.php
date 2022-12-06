<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/15/2018
 * Time: 9:48 PM
 */

namespace Transaction;

Interface ManagerInterface {

    public function modify(Row $transaction, Fields $transaction_updates, $user_id);
}