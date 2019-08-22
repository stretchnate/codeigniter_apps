<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 7/28/2019
 * Time: 7:40 PM
 */

/**
 * Class Home
 *
 * @author stretch
 */
class Home extends N8_Controller {

    /**
     * Home constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('home');
    }

    /**
     *
     */
    public function fetchAccountDisplay() {
        $this->auth->restrict();
        $service_model = new \Service\Home($this->session->user_id);
        $accounts = $service_model->fetchAccountDisplay($this->session->last_update);

//        echo json_encode($accounts);
        echo "<pre>".print_r($accounts, true)."</pre>";
    }
}