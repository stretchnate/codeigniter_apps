<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/7/18
 * Time: 8:09 PM
 */

namespace API\REST;

/**
 * Class Plaid
 *
 * @package API\REST
 */
abstract class Plaid extends \API\REST {

    /**
     * Plaid constructor.
     */
    public function __construct() {
        parent::__construct('Plaid');
    }

    /**
     * @return mixed|void
     */
    protected function start() {
        $this->ch = curl_init();

        curl_setopt($this->ch,CURLOPT_FORBID_REUSE, true);
        curl_setopt($this->ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }

    /**
     * @param $token
     * @param string $token_type
     * @return array
     */
    protected function dataArray($token, $token_type = 'access_token') {
        $creds = json_decode($this->vendor_data->getValues()->getCredentials());
        $data = [
            'client_id' => $creds->client_id,
            'secret' => $creds->secret,
            $token_type => $token
        ];

        return $data;
    }

    /**
     * @param $response
     * @return mixed|\Plaid\Auth
     * @throws \Exception
     */
    protected function parseResponse($response) {
        $response = json_decode($response);

        if(isset($response->error_type)) {
            throw new \Exception($response->error_message, EXCEPTION_CODE_ERROR);
        }

        return $response;
    }
}