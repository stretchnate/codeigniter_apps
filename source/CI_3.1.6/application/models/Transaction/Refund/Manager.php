<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/15/2018
 * Time: 9:50 PM
 */

namespace Transaction\Refund;

use Transaction\ManagerInterface;
use Transaction\Row;
use Transaction\Structure;

class Manager implements ManagerInterface {

    public function modify(Row $transaction, Structure $transaction_updates) {
        // TODO: Implement modify() method.
    }
}