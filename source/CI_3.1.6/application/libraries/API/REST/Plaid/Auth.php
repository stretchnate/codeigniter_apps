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

    /**
     * @param $token
     * @return \Plaid\Auth
     * @throws \Exception
     */
    public function getAuth($token) {
        $this->start();

        $postfields = $this->dataArray($token);

        $response = $this->post($this->target, json_encode($postfields));

        return new \Plaid\Auth($this->parseResponse($response));
    }

    /**
     * @param $public_token
     * @return mixed|\Plaid\Auth
     * @throws \Exception
     */
    public function exchangeToken($public_token) {
        $response = $this->post('item/public_token/exchange', json_encode($this->dataArray($public_token, 'public_token')));

        return $this->parseResponse($response);
    }
}