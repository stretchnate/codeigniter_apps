<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/12/18
 * Time: 9:13 PM
 */

namespace API\REST\Plaid;

use API\REST\Plaid;

class Income extends Plaid {

    private $target = '/income/get';

    /**
     * @return \Plaid\Income
     * @throws \Exception
     */
    public function getIncome() {
        $this->start();

        $postfields = $this->dataArray($this->vendor_data->getCredentials()->token);

        $response = $this->post($this->target, json_encode($postfields));

        return new \Plaid\Income($this->parseResponse($response));
    }
}