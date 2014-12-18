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
		$check = $this->ACCT->checkExistingAccount($this->session->userdata('user_id')); // check for exisiting account
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

		$this->load->view('header',$data);
		$this->load->view('/account/newAccountVW');
		$this->load->view('footer');
	}

	/**
	 * creates a new account
	 */
	function createNewAccount() {
		$this->auth->restrict();

		$this->load->model("Book_info");
		$this->load->model("accounts", "ACCT", TRUE);
		
		$response = array('success' => false, 'message' => "there was a problem adding your account");

		$account_name = $this->input->post("name");
		$pay_schedule = $this->input->post("pay_schedule");
		$owner        = $this->session->userdata("user_id");

		$data = $this->ACCT->createNewAccount($account_name, $pay_schedule, $owner);

		if($data) {
			if($data["num_rows"] > 1) {
				$response["message"] = "Duplicate account detected, please use a different name";
			} else if($data["new_account_id"] > 1) {
				$response["success"] = true;
				$response["message"] = "Account successfully created";
			}
		}

		echo json_encode($response);
	}
}

?>
