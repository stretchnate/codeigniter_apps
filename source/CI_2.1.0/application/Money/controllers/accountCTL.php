<?php
class AccountCTL extends N8_Controller {

	function AccountCTL() {
		parent::__construct();
	}

	function checkAccountName() {
		$response['success'] = false;
		$response['message'] = "";
		$this->load->model('Book_info');
		$this->load->model("accounts", "ACCT", TRUE);
		$check = $this->ACCT->checkExistingAccount($this->session->userdata('user_id'), $this->input->post('name')); // check for exisiting account
		if($check > 0) {
			$response['message'] = "this account already exists, please try another name.";
		} else {
			$response['success'] = true;
			// $response['message'] = "this account name is available";
		}
		unset($_POST['name']);
		echo json_encode($response);
	}

	/**
	 * displays the create account form
	 */
	function addNewAccount() {
		$this->auth->restrict();

		$data['scripts'] = $this->jsincludes->newAccount();
		$data['youAreHere'] = "Add New Account";
		$data['title'] = "Add New Account";
		$data['logged_user'] = $this->session->userdata('logged_user');

		$this->load->view('header',$data);
		$this->load->view('/account/newAccountVW');
		$this->load->view('footer');
	}

	/**
	 * edit an account
	 * @param int $account_id
	 */
	public function editAccount($account_id) {
		$this->auth->restrict();
		$account_dm = new Budget_DataModel_AccountDM($account_id);

		$data['scripts'] = $this->jsincludes->newAccount();
		$data['youAreHere'] = "Edit Account ".$account_dm->getAccountName();
		$data['title'] = "Edit Account ".$account_dm->getAccountName();
		$data['logged_user'] = $this->session->userdata('logged_user');

		$this->load->view('header',$data);
		$this->load->view('/account/newAccountVW', array('account_dm' => $account_dm));
		$this->load->view('footer');
	}

	/**
	 * creates a new account
	 */
	function saveAccount() {
		$this->auth->restrict();
		$account_dm = new Budget_DataModel_AccountDM();
		if($this->input->post('account_id')) {
			$account_dm->loadAccount($this->input->post('account_id'));
		}

		$this->load->model("Book_info");

		$response = array('success' => false, 'message' => "there was a problem saving your account");

		$account_dm->setAccountName($this->input->post("name"));
		$account_dm->setPayScheduleCode($this->input->post("pay_schedule"));

		if(!$account_dm->getID()) {
			$account_dm->setOwnerId($this->session->userdata("user_id"));
		}

		if($account_dm->saveAccount()) {
			$response["success"] = true;
			$response["message"] = "Account successfully saved";
		}

		echo json_encode($response);
	}
}
