<?php

class Report extends N8_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('html');
		$this->load->library('utilities');

		$this->load->view('report');
		$CI =& get_instance();
		$this->view = new ReportView($CI, $this->fetchAccountsList());
	}

    /**
     * main report view
     */
	function index() {
		$this->auth->restrict();

		$header_data['title'] = "Reports";
		$header_data['scripts'] = $this->jsincludes->report();
		$header_data['logged_user'] = $this->session->userdata('logged_user');

		$this->view->renderView();
	}

    /**
     * @return array
     * @throws Exception
     */
	private function fetchAccountsList() {
        $account_iterator = new \Budget\AccountIterator($this->session->userdata("user_id"));
        $account_iterator->load();

        $list = [];
        while($account_iterator->valid()) {
            $list[$account_iterator->current()->getAccountId()] = $account_iterator->current()->getAccountName();
            $account_iterator->next();
        }

        return $list;
    }
}

/* End of file report.php */
/* Location: ./system/application/controllers/report.php */
