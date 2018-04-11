<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/9/18
 * Time: 8:48 PM
 */

class Plaid extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function getAccessToken() {
        $data = new stdClass();
        $data->success = false;
        $data->message = 'There was a problem linking your account.';

        try {
            $api = new \API\REST\Plaid\Auth();

            $response = $api->exchangeToken($this->input->post('public_token', true));

            if($response->access_token && $response->item_id) {
                $connection = new \Plaid\Connection();
                $connection->getValues()->setItemId($response->item_id)
                    ->setAccessToken($response->access_token);

                $connection->save();

                $data->success = true;
                $data->message = '';
            }
        } catch(Exception $e) {
            if($e->getCode() === EXCEPTION_CODE_VALIDATION) {
                $data->message = $e->getMessage();
            } else {
                log_message('error', $e->getMessage());
                log_message('error', $e->getTraceAsString());
            }
        }

        echo json_encode($data);
    }
}