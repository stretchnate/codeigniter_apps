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

		$this->view->setTitle("Reports");
		$this->view->setScripts($this->jsincludes->report());

		$this->view->renderView();
	}

    /**
     * @return array
     * @throws Exception
     */
	private function fetchAccountsList() {
        $account_iterator = new \Budget\AccountIterator($this->session->userdata("user_id"));
        $account_iterator->load();

        return $account_iterator;
    }

    /**
     * fetch the category dropdown
     */
    public function fetchCategoryDropdown() {
	    try {
	        $account = new Budget_DataModel_AccountDM($this->input->post('account_id'), $this->session->userdata['user_id']);
	        $account->loadCategories();
	        echo json_encode(['success' => true, 'data' => $this->view->buildCategoriesSelect($account), 'message' => '']);
        } catch(Exception $e) {
	        log_message('error', $e->getMessage());
            echo json_encode(['success' => false, 'data' => null, 'message' => '']);
        }
    }
}

/* End of file report.php */
/* Location: ./system/application/controllers/report.php */
