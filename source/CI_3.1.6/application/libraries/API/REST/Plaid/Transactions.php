<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/12/18
 * Time: 8:58 PM
 */

namespace API\REST\Plaid;


use API\REST\Plaid;

class Transactions extends Plaid {

    private $target = '/transactions/get';

    /**
     * @return \Plaid\TransactionResponse
     * @throws \Exception
     */
    public function get() {
        $this->start();

        $postfields = $this->dataArray($this->vendor_data->getCredentials()->token);

        $response = $this->post($this->target, json_encode($postfields));

        return new \Plaid\TransactionResponse($this->parseResponse($response));
    }
}