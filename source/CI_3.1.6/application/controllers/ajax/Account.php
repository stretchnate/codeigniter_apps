<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 9/4/2019
 * Time: 7:00 PM
 */

/**
 * Class Account
 *
 * @author stretch
 */
class Account extends N8_Controller {

    /**
     * @var \Service\Account
     */
    private $service_model;

    /**
     * Account constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->service_model = new \Service\Account();
    }

    /**
     * @param $id
     */
    public function get($id) {
        try {
            $dm = new Budget_DataModel_AccountDM($id, $this->session->user_id);
            $dm->loadCategories();

            $this->JSONResponse($this->service_model->convertToJSON($dm));
        } catch(Exception $e) {
            log_message('error', $e);
            $this->JSONResponse(null, 500);
        }
    }

    /**
     * fetch all account objects - echos a JSON response
     */
    public function fetchAll() {
        try {
            $iterator = new \Account\Iterator(['owner_id' => $this->input->post('owner_id')]);
            $accounts = [];
            while($iterator->valid()) {
                $accounts[] = $this->service_model->convertToJSON($iterator->current());
                $iterator->next();
            }

            $this->JSONResponse($accounts);
        } catch(Exception $e) {
            log_message('error', $e);
            $this->JSONResponse(null, 500);
        }
    }
}