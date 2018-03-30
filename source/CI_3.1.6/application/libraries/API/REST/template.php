<?php

/**
 * Class REST_Plaid
 *
 * @author Stretch dnate@integrafinancialservice.com
 */
class REST_Plaid extends REST {

    const VENDOR_NAME = 'Plaid';

    public function __construct() {
        parent::__construct(self::VENDOR_NAME);
    }

    /**
     * @param $public_token
     * @return mixed
     */
    public function exchangeToken($public_token) {
        $response = $this->executeCurlPost('item/public_token/exchange', json_encode($this->dataArray($public_token, 'public_token')));

        return $this->parseResponse($response);
    }

    /**
     * @param $access_token
     * @return Plaid_API_Auth
     */
    public function retrieveAuth($access_token) {
        $response = $this->executeCurlPost('auth/get', json_encode($this->dataArray($access_token)));

        return new Plaid_API_Auth($this->parseResponse($response));
    }

    /**
     * @param string $access_token
     * @param array $account_ids
     * @param DateTime $start_date
     * @param DateTime|null $end_date
     * @param int $count
     * @param int $offset
     * @return Plaid_API_Transactions
     */
    public function retrieveTransactions($access_token, array $account_ids, DateTime $start_date, DateTime $end_date = null, $count = 30, $offset = 0) {
        $data = $this->dataArray($access_token);
        $data['start_date'] = $start_date->format('Y-m-d');
        $data['end_date'] = $end_date->format('Y-m-d');
        $data['options']['count'] = $count;
        $data['options']['offset'] = $offset;

        if(!empty($account_ids)) {
            $data['options']['account_ids'] = $account_ids;
        }

        $response = $this->executeCurlPost('transactions/get', json_encode($data));

        $result = $this->parseResponse($response);

        return new Plaid_API_Transactions($result);
    }

    /**
     * @param $institution_name
     * @param $public_key
     * @return Plaid_API_Institutions
     */
    public function institutionSearch($institution_name, $public_key) {
        $data = [
            "query" => $institution_name,
            "products" => ["auth", "transactions"],
            "public_key" => $public_key
        ];

        $response = $this->executeCurlPost('institutions/search', json_encode($data));

        $result = $this->parseResponse($response);

        return new Plaid_API_Institutions($result);
    }

    /**
     * @param $access_token
     * @return Plaid_API_Identity
     */
    public function retrieveIdentity($access_token) {
        $data = $this->dataArray($access_token);

        $response = $this->executeCurlPost('identity/get', json_encode($data));

        $result = $this->parseResponse($response);

        return new Plaid_API_Identity($result);
    }

    /**
     * this method allows us to de-authorize plaid for any item (an item is a series of accounts associated with a single bank login)
     *
     * @param $access_token
     * @return stdClass
     */
    public function deleteItem($access_token) {
        $data = $this->dataArray($access_token);

        $response = $this->executeCurlPost('item/delete', json_decode($data));

        return $this->parseResponse($response);
    }

    /**
     * @param $token
     * @param string $token_type
     * @return array
     */
    protected function dataArray($token, $token_type = 'access_token') {
        $data = [];
        $data['client_id'] = $this->vendor_data->credentials->outbound->client_id;
        $data['secret'] = $this->vendor_data->credentials->outbound->secret;
        $data[$token_type] = $token;

        return $data;
    }

    /**
     * start the cURL process
     */
    protected function startCURL() {
        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($this->ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    }

    /**
     * @param $response
     * @param null $key
     * @throws Exception
     * @return stdClass
     */
    protected function parseResponse($response, $key = null) {
        $response = json_decode($response);

        if(isset($response->error_type)) {
            throw new Exception($response->error_message, $response->http_code);
        }

        return $response;
    }
}
