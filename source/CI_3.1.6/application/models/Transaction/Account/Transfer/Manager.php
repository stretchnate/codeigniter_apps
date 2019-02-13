<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/15/2018
 * Time: 9:52 PM
 */

namespace Transaction\Account\Transfer;

use Transaction\ManagerInterface;
use Transaction\Row;
use Transaction\Structure;

class Manager implements ManagerInterface {

    public function modify(Row $transaction, Structure $transaction_updates, $user_id) {
        // TODO: Implement modify() method.
    }
}