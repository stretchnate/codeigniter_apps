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
        $js = new Jsincludes();

        $this->load->view('report');
        $CI =& get_instance();
        $this->view = new ReportView($CI, $this->fetchAccountsIterator());
        $this->view->setScripts($js->reports());
    }

    public function index() {
        try {
            echo $this->view->renderView();
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