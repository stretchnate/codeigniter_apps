<?php
class Book extends N8_Controller {

	function __construct() {
		parent::__construct();
	}

	function getBookInfo($id){
		$this->auth->restrict();//make sure user is logged in.
		try {
			$category_dm = new Budget_DataModel_CategoryDM($id, $this->session->userdata('user_id'));

			$this->load->model("accounts", "ACCT", TRUE);
			$this->load->model('funds_operations','Fops',TRUE);
			$this->load->model('notes_model', 'NM', TRUE);

			$data = $this->ACCT->getAccountData($id);

			$data->parentAccount = $this->ACCT->getAccount($data->account_id);

			$home = new Budget_BusinessModel_Home();
			$home->loadAccounts($this->session->userdata('user_id'));
			$props['accounts'] = $home->getAccounts();
			$data->accounts = $this->ACCT->getAccountsAndDistributableCategories($this->session->userdata('user_id'));

			$t_grid = new TransactionsGrid($id, "category");
			$t_grid->run();

			$data->transactions = $t_grid->getTransactionsGrid();

			$props['youAreHere'] = $data->bookName;
			$props['scripts'] = $this->jsincludes->books();
			$props['title'] = $data->bookName;
			$props['links'] = $this->utilities->createLinks('main_nav');
			$props['logged_user'] = $this->session->userdata('logged_user');

			$notes['notes'] = $this->NM->getAllNotes($this->session->userdata('user_id'), $id);
			$notes['bookId'] = $id;

			$data->due_date = $category_dm->getNextDueDate();
			$this->load->view('header',$props);
			$this->load->view('CategoryDetail', $data);
			$this->load->view('footer', $notes);
		} catch(Exception $e) {
			show_error("we were unable to load this page", 500);
			log_error('error', $e->getMessage());
		}
	}

	/*
	 * creates the category add form
	 * @todo  once a cache module is added need to update this method to use cash for $message
	 * @return void
	 * @access public
	 */
	public function newBookForm(){
		$this->auth->restrict();
		$this->load->model("accounts", "ACCT", TRUE);
		$this->load->view('budget/category/newCategoryVW');

		$CI          =& get_instance();
		$helper = new \Plaid\Categories\Helper();
		$category_api = new \API\REST\Plaid\Categories();
		$categories = $helper->createCategoriesOptions($category_api->getCategories());
		$category_vw =  new Budget_Category_NewCategoryVW($CI, $categories);
		$category_vw->setScripts($this->jsincludes->newBook());
		$category_vw->setTitle("Add New Category");
		$category_vw->setAction("/book/createCategory/");

		$accounts = $this->ACCT->getAccounts($this->session->userdata("user_id"));
		$category_vw->setAccounts($accounts);
		// $category_vw->setErrors($message);
		// $category_vw->setCategoryDM($category_dm);
		$category_vw->renderView();
	}

	/*
	 * creates the category edit form
	 * @todo  once a cache module is added need to update this method to use cash for $message
	 * @param int $id
	 * @param string $message (optional)
	 * @return void
	 * @access public
	 */
	public function editBook($id, $message = null) {
		$this->auth->restrict();
		try {
			$this->load->model("accounts", "ACCT", TRUE);

			$category_dm = new Budget_DataModel_CategoryDM($id, $this->session->userdata('user_id'));

			$this->load->view('budget/category/newCategoryVW');

			$CI =& get_instance();
            $helper = new \Plaid\Categories\Helper();
            $category_api = new \API\REST\Plaid\Categories();
            $categories = $helper->createCategoriesOptions($category_api->getCategories());
			$category_vw = new Budget_Category_NewCategoryVW($CI, $categories);
			$category_vw->setScripts($this->jsincludes->editBook());
			$category_vw->setTitle("Edit ".$category_dm->getCategoryName());
			$category_vw->setAction("/book/saveChange/{$category_dm->getCategoryId()}/");
			$category_vw->setErrors($message);
			$category_vw->setCategoryDM($category_dm);
			$category_vw->setAccounts($this->ACCT->getAccounts($this->session->userdata("user_id")));

			$category_vw->renderView();
		} catch(Exception $e) {
			show_error("we were unable to load this page", 500);
			log_error('error', $e->getMessage());
		}
	}

	function checkName() {
		$response['success'] = false;
		$response['message'] = "";
		$this->load->model('Book_info', 'BI',TRUE);
		$check = $this->BI->checkExisting($this->session->userdata('user_id'), $this->input->post('account'), $this->input->post('name')); // check for exisiting account
		if($check > 0) {
			$response['message'] = "this account already exists, please try another name.";
		} else {
			$response['success'] = true;
			//$response['message'] = "this account is available";
		}
		unset($_POST['name']);
		echo json_encode($response);
	}

	/**
	 * create a new category
	 */
	function createCategory(){
		$response['success'] = false;
		$response['message'] = "";
		$start_amount = $this->input->post("startAmt");

		$rules = array();
		$rules["name"]          = array("field" => "name", "label" => "Category Name", "rules" => "trim|required");
		$rules["account"]       = array("field" => "account", "label" => "Into Account", "rules" => "required");
		$rules["nec"]           = array("field" => "nec", "label" => "Amount Necessary", "rules" => "trim|required|numeric");
		$rules["startAmt"]      = array("field" => "startAmt", "label" => "Starting Amount", "rules" => "trim|required|numeric");
		$rules["bill_schedule"] = array("field" => "bill_schedule", "label" => "Fill Category", "rules" => "trim|required");
		if($this->input->post('bill_schedule') != 'per_check') {
			$rules["dueDay"]    = array("field" => "dueDay", "label" => "Next Due Date", "rules" => "trim|required");
		}

		if($this->validate($rules)){
			$e = 0;
			$this->load->model('Book_info', 'BI',TRUE);
			//check for existing category...I think
            $category_type = $this->input->post('category_type') ? $this->input->post('category_type') : null;
			$check = $this->BI->checkExisting($this->session->userdata('user_id'), $this->input->post('account'), $this->input->post('name'), $category_type); // check for exisiting category
			if($check < 1) {
				//subtract from account if necessary
				if($start_amount > 0) {
					$account_dm = new Budget_DataModel_AccountDM($this->input->post('account'), $this->session->userdata('user_id'));

					if($start_amount > $account_dm->getAccountAmount()) {
						$start_amount = $account_dm->getAccountAmount();
					}

					$account_amount = subtract($account_dm->getAccountAmount(), $start_amount, 2);
					$account_dm->setAccountAmount($account_amount);
					$account_dm->saveAccount();
				}
				if($e < 1) {
					//add category to account
					$category_dm = new Budget_DataModel_CategoryDM();
					$category_dm->setParentAccountId($this->input->post("account"));
					$category_dm->setCategoryName($this->input->post('name'));
					$category_dm->setCurrentAmount($start_amount);
					$category_dm->setAmountNecessary($this->input->post('nec'));
					$category_dm->setInterestBearing($this->input->post('interest'));
					$category_dm->setPriority($this->input->post('priority'));
					$category_dm->setOwnerId($this->session->userdata('user_id'));
					$category_dm->setDueDay($this->input->post('dueDay'));
					$category_dm->setPlaidCategory($this->input->post('category_type'));
					$category_dm->setActive(1);

					$category_dm->setDueMonths($this->calculateDueMonths(
						$this->input->post('dueDay'),
						$this->input->post('bill_schedule'))
					);
					$category_dm->saveCategory();

					if($start_amount > 0) {
						//add the transaction
						$transaction = new Budget_DataModel_TransactionDM();
						$transaction->setFromAccount($account_dm->getAccountId());
						$transaction->setToCategory($category_dm->getCategoryId());
						$transaction->setOwnerId($this->session->userdata("user_id"));
						$transaction->setTransactionAmount((float)$start_amount);
						$transaction->setTransactionDate( date("Y-m-d H:i:s") );
						$transaction->setTransactionInfo("Initial deposit into new category " . $category_dm->getCategoryName());
						$transaction->saveTransaction();
					}
				}
			} else {
				$response['message'] = "this category already exists, please try another name. ";
				$e++;
			}

			if($e == 0) {
				$response['success'] = true;
				$response['message'] = "Category successfully added.";
			}
		} else {
			$response['message'] =  $this->form_validation->error_string();
		}

		echo json_encode($response);
	}

	/**
	 * calculate the due months of a category
	 *
	 * @param string $due_date
	 * @param string $bill_schedule
	 * @return array
	 */
	private function calculateDueMonths($due_date, $bill_schedule) {
		$result = array();
		$date = $due_date ? new DateTime($due_date) : new DateTime();
		switch($bill_schedule) {
			case 'quarterly':
				$limit = 4;
				$interval = new DateInterval('P3M');
				break;
			case 'semi_annual':
				$limit = 2;
				$interval = new DateInterval('P6M');
				break;
			case 'annual':
				$result = array($date->format('n'));
				break;
			case 'per_check':
			case 'monthly':
			default:
				$limit = 12;
				$interval = new DateInterval('P1M');
				break;
		}

		if(isset($limit)) {
			for($i = 1; $i <= $limit; $i++) {
				$result[] = $date->add($interval)->format('n');
			}
		}

		sort($result);
		return $result;
	}

	function enableBook($id){
		$this->auth->restrict();
		$this->load->model('Book_edit', 'BE',TRUE);
		$data = $this->BE->bookOffOn($id);
		if($data['success'] == false){
			$this->load->view('error_view',$data);
		} else if ($data['success'] == true) {
			header("Location: /book/editBook/$id");
			exit();
		}
	}

	function saveChange($id) {
		$this->auth->restrict();

		$due_date = null;
		if(preg_match('/(\/|-)/', $this->input->post('dueDay'))) {
			$due_date = $this->input->post('dueDay');
		}
		try {
			$category_dm = new Budget_DataModel_CategoryDM($id, $this->session->userdata('user_id'));

			$category_dm->setParentAccountId($this->input->post('account'));
			$category_dm->setCategoryName($this->input->post('name'));
			$category_dm->setAmountNecessary($this->input->post('nec'));
			$category_dm->setDueDay($due_date);
			$category_dm->setPriority($this->input->post('priority'));
			$category_dm->setDueMonths(
				$this->calculateDueMonths(
					$due_date,
					$this->input->post('bill_schedule')
				)
			);

			if($category_dm->saveCategory() !== false) {
				header('Location:/book/getBookInfo/'.$id.'/');
				// $this->getBookInfo($id);
			} else {
				//@todo  once a cache module is added need to update this method to use cash for $message
				$message = '';
				foreach($category_dm->getErrors() as $error) {
					// $this->setError($error);
					$message .= $error;
				}

				// $this->editBook($id);
				header('location:/book/editBook/'.$id.'/'.$message.'/');
			}
			exit;
		} catch(Exception $e) {
			show_error("There was a problem saving the change.", 500);
			log_error('error', $e->getMessage());
		}
	}

	function transactionCleared() {
		$this->auth->restrict();
		$this->load->model('Transactions', 'TR', TRUE);
		$response['success'] = $this->TR->transactionClearedBank($this->input->post('transaction_id'));
		if( !$response['success'] ) {
			$response['message'] = "Unable to modify transaction $id";
		}
		echo json_encode($response);
	}
}
