<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 7/16/2018
 * Time: 7:20 PM
 */

class Report extends N8_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('html');
        $this->load->library('utilities');

        $this->load->view('report');
        $CI =& get_instance();
        $this->view = new ReportView($CI, $this->fetchAccountsIterator());
    }

    public function index() {
        try {
            echo json_encode($this->view->renderView());
        } catch(Exception $e) {

        }
    }

    /**
     * @return array
     * @throws Exception
     */
    private function fetchAccountsIterator() {
        $account_iterator = new \Budget\AccountIterator($this->session->userdata("user_id"));
        $account_iterator->load();

        return $account_iterator;
    }


}