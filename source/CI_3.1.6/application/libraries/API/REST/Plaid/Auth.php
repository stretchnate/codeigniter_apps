<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/12/18
 * Time: 8:52 PM
 */

namespace API\REST\Plaid;


use API\REST\Plaid;

/**
 * Class Auth
 *
 * @package API\REST\Plaid
 */
class Auth extends Plaid {

    private $target = '/auth/get';

    public function get() {
        $this->start();

        $postfields = [
            'client_id' => $this->vendor_data->getClientId(),
            'secret' => $this->vendor_data->getSecret(),
            'access_token' => $this->vendor_data->getAccessToken(),
            'options' => null,
            'account_ids' => null
        ];
        return $this->formatResponse($this->executeCurlPOST($this->target));
    }

    /**
     * @param $response
     * @return mixed|\Plaid\Auth
     */
    public function formatResponse($response) {
        return new \Plaid\Auth($response);
    }
}