<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/15/2018
 * Time: 9:53 PM
 */

namespace Transaction\Account\Deposit;

use Transaction\ManagerInterface;
use Transaction\Row;
use Transaction\Fields;

class Manager implements ManagerInterface {

    public function modify(Row $transaction, Fields $transaction_updates, $user_id) {
        // TODO: Implement modify() method.
    }
}