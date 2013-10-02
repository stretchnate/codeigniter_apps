<?php

class CategoryCTL extends Controller {

	function CategoryCTL() {
		parent::__construct();
	}

	/**
	 * main method called when adding a new category
	 */
	function addCategoryToAccount() {
		$this->auth->restrict();
		$rules = array();
		$this->load->model("accounts", "ACCT", TRUE);

		$response['success'] = false;
		$response['message'] = "";
		$interest = $this->input->post("interest");
		$start_amount = $this->input->post("startAmt");
		$from_holding = $this->input->post("frmHolding");
		$category_name = $this->input->post("name");

		$parent_account = $this->ACCT->getAccount($this->input->post("account"));

		$rules[] = array(
		    'field' => 'name',
		    'label' => 'Name',
		    'rules' => 'trim|required');

		if (isset($interest) && $interest == "yes") {
			$rules[] = array(
			    'field' => 'rate',
			    'label' => 'Rate',
			    'rules' => 'required|numeric');

			$rules[] = array(
			    'field' => 'rateType',
			    'label' => 'Rate Type',
			    'rules' => 'required');

			$rules[] = array(
			    'field' => 'totalOwed',
			    'label' => 'Total Owed',
			    'rules' => 'required|numeric');
		}
		if ($this->validate($rules)) {
			$this->ACCT->transactionStart();
			$this->load->model("budget/categoryDM", "CAT", TRUE);
			$check_existing = $this->CAT->checkExisitingCategory($category_name, $this->session->userdata("user_id"));
			if ($check_existing === false) {
				//create category
				if ($from_holding && ($parent_account->account_amount < $start_amount)) {
					$start_amount = $parent_account->account_amount;
				}
				$data = array(
				    'bookName' => $category_name,
				    'bookAmtNec' => $this->input->post('nec'),
				    'bookAmtCurrent' => $start_amount,
				    'InterestBearing' => $interest,
				    'priority' => $this->input->post('priority'),
				    'ownerId' => $this->session->userdata("user_id"),
				    'due_day' => $this->input->post('dueDay'),
				    'account_id' => $parent_account->account_id);
//@TODO do something with interest, see book->createCategory()
				$create_category = $this->createCategory($data);

				//transfer funds from account (if necessary)
				if ($create_category > 0 && $from_holding) {
					$parent_account->account_amount = number_format(((float) $parent_account->account_amount - (float) $start_amount), 2);
					$acct_update = $this->ACCT->saveAccount($parent_account);

					//add transaction record(s)
					if ($acct_update) {
						$props = array('ownerId' => $this->session->userdata('user_id'),
						    'bookTransAmt' => $start_amount,
						    'TransType' => 'a',
						    'bookId' => $create_category,
						    'bookTransPlace' => 'Starting Amount');
						$this->BI->newTransaction($props);
					} else {
						$response["message"] = "Unable to transfer funds from parent account";
					}
				}
			} else {
				$response["message"] = "Category name already exists";
			}
			$this->ACCT->transactionEnd();
		} else {
			$response['message'] = $this->validation->error_string;
		}
		echo json_encode($response);
	}

	/**
	 * validate and create the new category
	 */
	function createCategory(&$data) {
		$this->load->model("budget/categoryDM", "CAT", TRUE);

		$this->CAT->insertNewCategory($data);
	}

	function deleteCategory() {

	}

	function categoryView() {

	}

	function moveCategoryToAccount() {

	}

	function editCategoryView() {

	}

}

?>
