<?php

class Report extends N8_Controller {

	function Report() {
		parent::__construct();
		$this->load->helper('html');
		$this->load->library('utilities');
	}

	function index() {
		$this->auth->restrict();

		$header_data['title'] = "Reports";
		$header_data['scripts'] = $this->jsincludes->report();

		$sidebar = new NavigationUlLIB('report');
		$header_data['sidebar_links'] = $sidebar->getUl();

		$this->load->view('header', $header_data);
		// $this->load->view('sidebar', $sidebar);
		$this->load->view('footer');
	}

	function transactions($run_report = FALSE) {
		$this->auth->restrict();

		$data = array();
		$this->load->model('book_info', "BI", TRUE);
		$this->load->model('accounts', "ACCT", TRUE);

		$header_data['title'] = "Transactions Report";
		$header_data['scripts'] = $this->jsincludes->report();

		$sidebar = new NavigationUlLIB('report');
		$header_data['sidebar_links'] = $sidebar->getUl();

		$transactions["transactions"] = null;
		if( $run_report == 'true' ) {
			$this->load->model('transactions', 'TRAN', TRUE);
			$rowsPerPage = 100;
			$offset = 0;
			$start_date = $this->input->post('start_date');
			$end_date   = $this->input->post('end_date');
			// $data['transactions'] = $this->TRAN->getTransactionsByDate($this->session->userdata('user_id'), $dates, $offset, $rowsPerPage, $this->input->post('account'));

			$t_grid = new TransactionsGrid($this->input->post('account'), "category", true);
			$t_grid->setStartDate(new DateTime($start_date));
			$t_grid->setEndDate(new DateTime($end_date));
			$t_grid->run();

			$transactions["transactions"] = $t_grid->getTransactionsGrid();
		}

		$data['accounts'] = $this->ACCT->getAccountsAndDistributableCategories($this->session->userdata('user_id'));
		$data['form_data']['action'] = '/report/transactions/true';

		$this->load->view('header', $header_data);
		// $this->load->view('sidebar', $sidebar);
		$this->load->view('reports', $data);
		$this->load->view('transactions', $transactions);
		$this->load->view('footer');
	}

	function deposits($run_report = FALSE) { //@TODO build deposits report
		$this->auth->restrict();

		$data = array();
		$this->load->model('book_info');
		$this->load->model('accounts', "ACCT", TRUE);

		$header_data['title'] = "Deposits Report";
		$header_data['scripts'] = $this->jsincludes->report();

		$sidebar = new NavigationUlLIB('report');
		$header_data['sidebar_links'] = $sidebar->getUl();

		if( $run_report ) {
			$this->load->model('deposits', 'DEP', TRUE);
			$rowsPerPage = 100;
			$offset = 0;
			$data['records'] = $this->DEP->getDeposits($this->session->userdata('user_id'), $this->input->post('account'),$this->input->post('start_date'), $this->input->post('end_date'), $offset, $rowsPerPage);
		}

		$data['form_data']['action'] = '/report/deposits/true';
		$data['accounts'] = $this->ACCT->getAccounts($this->session->userdata('user_id'));

		$this->load->view('header', $header_data);
		// $this->load->view('sidebar', $sidebar);
		$this->load->view('reports', $data);
		$this->load->view('transactions/deposits', $data);
		$this->load->view('footer');
	}
}

/* End of file report.php */
/* Location: ./system/application/controllers/report.php */
