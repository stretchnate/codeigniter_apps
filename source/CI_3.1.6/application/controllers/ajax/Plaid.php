<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/9/18
 * Time: 8:48 PM
 */

class Plaid extends CI_Controller {

    /**
     * Plaid constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * exchange the public token for an access token
     */
    public function getAccessToken() {
        $data = new stdClass();
        $data->success = false;
        $data->message = 'There was a problem linking your account.';

        try {
            $api = new \API\REST\Plaid\Auth();

            $metadata = new \Plaid\Metadata($this->input->post('metadata', true));
            $response = $api->exchangeToken($this->input->post('public_token', true));

            if($response->access_token && $response->item_id) {
                $creator = new \Plaid\Account\Creator();
                $creator->run($metadata, $response, $this->session->userdata('user_id'));

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