<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/12/18
 * Time: 9:13 PM
 */

namespace API\REST\Plaid;

use API\REST\Plaid;

class Balance extends Plaid {

    private $target = '/accounts/balance/get';

    /**
     * @return \Plaid\Balace
     * @throws \Exception
     */
    public function get() {
        $this->start();

        $postfields = $this->dataArray($this->vendor_data->getCredentials()->token);

        $response = $this->post($this->target, json_encode($postfields));

        return new \Plaid\Balace($this->parseResponse($response));
    }
}