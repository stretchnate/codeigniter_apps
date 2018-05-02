<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 5/1/2018
 * Time: 7:12 PM
 */

namespace Plaid\Connection;

class Handler {

    public function setTransactionsUpdatedDate($item_id, $plaid_account_id, $date) {
        $values = new \Plaid\Connection\Values();
        $values->setItemId($item_id)
            ->setPlaidAccountId($plaid_account_id);
        $connection = new \Plaid\Connection($values);
        $connection->getValues()->setTransactionsUpdated($date);
        $connection->save();
    }
}