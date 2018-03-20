<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/12/18
 * Time: 9:12 PM
 */

namespace API\REST\Plaid;

use API\REST\Plaid;

class Identity extends Plaid {

    private $target = '/identity/get';

    /**
     * @return \Plaid\IdentityResponse
     * @throws \Exception
     */
    public function get() {
        $this->start();

        $postfields = $this->dataArray($this->vendor_data->getCredentials()->token);

        $response = $this->post($this->target, json_encode($postfields));

        return new \Plaid\IdentityResponse($this->parseResponse($response));
    }
}