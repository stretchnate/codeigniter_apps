<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/9/18
 * Time: 9:14 PM
 */

class Plaid extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * transactions webhook endpoint
     */
    public function transactions() {
        $data = $this->input->post(null, true);
        try {
            if ($data->webhook_type == 'TRANSACTIONS') {
                $values = new \Plaid\Connection\Values();
                $values->setItemId($data->item_id);
                $connection = new \Plaid\Connection($values);
                $connection->getValues()->setTransactionsReady($data->webhook_code);
                $connection->save();
            }
        } catch(Exception $e) {
            log_message('error', $e->getMessage());
            //need to send email here or something to indicate there is a problem
        }
    }
}