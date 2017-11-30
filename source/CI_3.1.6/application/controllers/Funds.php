<?php
class Funds extends N8_Controller {

    /**
     * constructor
     */
	function Funds() {
		parent::__construct();
		$this->load->library('account');
		$this->load->model("Book_info");
	}

    /**
     * displays the add new funds page
     *
     * @return void
     */
	function index(){
		$this->auth->restrict();
		$data['youAreHere'] = "Add New Funds";
		$data['scripts'] = $this->jsincludes->newFunds();
		$data['title'] = "Add New Funds";
		$data['logged_user'] = $this->session->userdata('logged_user');

		$this->load->model('accounts', 'ACCT', TRUE);

		$funds_data['accounts'] = $this->ACCT->getAccounts($this->session->userdata("user_id"));

		$this->load->view('header',$data);
		$this->load->view('newFunds', $funds_data);
		$this->load->view('footer');
	}

    /**
     * saves the funds to the account
     *
     * @return void
     */
	function addFunds(){
		$this->auth->restrict();
		$this->load->model("accounts", "ACCT", TRUE);

		$ownerId = $this->session->userdata('user_id');
		$account_id = $this->input->post("account");

		$account_model = $this->ACCT->getAccount($account_id);

		$date = date("Y-m-d H:i:s");
		if($this->input->post('date')) {
			$date = date("Y-m-d H:i:s", strtotime($this->input->post('date')));
		}
		if(empty($_POST['net'])) {
			$net = $this->input->post('gross');
		} else {
			$net = $this->input->post('net');
		}

		$account_model->account_amount = (float)$account_model->account_amount + (float)$net;
		if($this->ACCT->saveAccount($account_model)) {
			$this->addDepositToAccount($account_id, $ownerId, $date, $net, $this->input->post('gross'), $this->input->post('source'));
		}

		header('location:/');
	}


	/**
	 * Adds funds to each account automatically
	 *
	 */
	function automaticallyDistributeFunds() {
		$this->auth->restrict();

		$account_dm = new Budget_DataModel_AccountDM($this->input->post("account"), $this->session->userdata('user_id'));
		$total      = 0;
		$errors     = array();
		$date       = date("Y-m-d H:i:s");

		if($this->input->post('date')) {
			$date = date("Y-m-d H:i:s", strtotime($this->input->post('date')));
		}

		if(!$account_dm->getPayScheduleCode()){
			$errors[] = "unable to get pay schedule, please try again.";
		} else {
			if(empty($_POST['net'])) {
				$net = preg_replace("/[,]+/", "", $this->input->post('gross'));
			} else {
				$net = preg_replace("/[,]+/", "", $this->input->post('net'));
			}

			$total = ((float) $account_dm->getAccountAmount()) + ((float) $net);

			$account_dm->setAccountAmount($total);
			$account_dm->saveAccount();

			$deposit_id = $this->addDepositToAccount(
														$account_dm->getAccountId(),
														$this->session->userdata('user_id'),
														$date,
														$net,
														$this->input->post('gross'),
														$this->input->post('source')
													);

			//add the transaction
			$transaction = new Budget_DataModel_TransactionDM();
			$transaction->setToAccount($account_dm->getAccountId());
			$transaction->setDepositId($deposit_id);
			$transaction->setOwnerId($this->session->userdata("user_id"));
			$transaction->setTransactionAmount((float)$net);
			$transaction->setTransactionDate($date);
			$transaction->setTransactionInfo("Deposit ".$deposit_id." into ".$account_dm->getAccountName());
			$transaction->saveTransaction();

			$account_dm->loadCategories();

			bcscale(2);

			$divider = 1;
			$category_dm_array = $account_dm->orderCategoriesByDueFirst($date);

			//loop through each category and update the current amount
			foreach($category_dm_array as $category_dms) {
				foreach($category_dms as $category) {
					if(count($errors) > 0) {
						break;
					}

					if($total > 0) {
						if($category->getCurrentAmount() < $category->getAmountNecessary()) {

							//determine if we need to use regular divider or simply top off the category
							if( ($category->getDaysUntilDue($date) <= $account_dm->getPayFrequency())
								|| ((( round($category->getAmountNecessary() / $divider, 2) ) + $category->getCurrentAmount()) > $category->getAmountNecessary()) ) {

								$depositAmount = subtract($category->getAmountNecessary(), $category->getCurrentAmount(),2);
							} else {
								$depositAmount = divide($category->getAmountNecessary(), $divider,2);
							}

							if($depositAmount > $total) {
								$depositAmount = $total;
							}

							$new_category_amount = add($depositAmount, $category->getCurrentAmount(),2);

							$category->setCurrentAmount($new_category_amount);
							$category->saveCategory();

							if($category->isErrors() === false) {
								// $total = $total - $depositAmount;
								$total = subtract($total, $depositAmount,2);
								$account_dm->setAccountAmount($total);
								$account_dm->saveAccount();

								if($account_dm->isErrors() === false) {

									//add the transaction
									$transaction = new Budget_DataModel_TransactionDM();
									$transaction->setToCategory($category->getCategoryId());
									$transaction->setFromAccount($account_dm->getAccountId());
									$transaction->setOwnerId($this->session->userdata("user_id"));
									$transaction->setTransactionAmount($depositAmount);
									$transaction->setTransactionDate($date);
									$transaction->setTransactionInfo("Automatically distributed funds from ".$account_dm->getAccountName()." account into ".$category->getCategoryName());
									$transaction->saveTransaction();

									$errors = $transaction->getErrors();
								} else {
									//rollback
									// $rollback = $category->getCurrentAmount() - $depositAmount;
									$rollback = subtract($category->getCurrentAmount(), $depositAmount,2);
									$category->setCurrentAmount($rollback);
									$category->saveCategory();

									// $total = $total + $depositAmount;
									$total = add($total, $depositAmount,2);

									$errors = $account_dm->getErrors();
								}
							} else {
								$errors = $category->getErrors();
							}
						}
					} else {
						break;
					}
				}
				$divider++;
			}

			if( (float)$total != (float)$account_dm->getAccountAmount()){
				$account_dm->setAccountAmount( (float)$total );
				$account_dm->saveAccount();
			}
		}

		if( count($errors) > 0 ) {
			$data = array();
			$data['error'] = implode("<br />", $errors);
			$props['youAreHere'] = "Add New Funds";
			$props['scripts'] = $this->jsincludes->newFunds();
			$props['title'] = "Add New Funds";

			$this->load->view('header',$props);
			$this->load->view('newFunds',$data);
			$this->load->view('footer');
		} else {
			header("Location: /");
		}
	}

    /**
     * adds a deposit to an account
     *
     * @param int $account
     * @param int $owner_id
     * @param string $date
     * @param float $net
     * @param float $gross
     * @param string $source
     * @return boolean
     */
	function addDepositToAccount($account, &$owner_id, &$date, &$net, &$gross, &$source) {
		$this->load->model('Funds_operations', 'Fops',TRUE);
		$data = array('ownerId' => $owner_id,
						'date' => $date,
						'source' => $source,
						'gross' => $gross,
						'net' => $net,
						'account_id' => $account);
		return $this->Fops->insertMain($owner_id, $data);
	}

	 /**
	 * this function replaces book/setBookInfo() and will handle account deductions as well as refunds and transfers from Bucket.
	 *
	 */
	public function updateAccountFunds() {
		$this->auth->restrict();

		try {
			$this->load->model('Funds_operations', 'Fops',TRUE);
			$this->load->model("accounts", "ACCT", TRUE);

			$parent_account = new Budget_DataModel_AccountDM($this->input->post("parent_account"), $this->session->userdata('user_id'));
			$category       = new Budget_DataModel_CategoryDM($this->input->post('id'), $this->session->userdata('user_id'));

			$requested_amount = $this->input->post('amount');

			$date = date("Y-m-d H:i:s");
			if($this->input->post('date')) {
				$date = $this->input->post('date')." ".date("G:i:s");
				$date = date("Y-m-d H:i:s", strtotime($date));
			}

			switch($_POST['operation']) {
				case 'addFromBucket':
					$type = 'a';
					$from = null;
					$to = $this->input->post('id');
					$refund = null;

					if($parent_account->getAccountAmount() < $requested_amount) {
						$requested_amount = $parent_account->getAccountAmount();
					}

					$parent_account->transactionStart();
					$parent_account->setAccountAmount($parent_account->getAccountAmount() - $requested_amount);

					$category->setCurrentAmount($category->getCurrentAmount() + $requested_amount);

					$parent_account->saveAccount();
					$category->saveCategory();

					if( $parent_account->isErrors() === false && $category->isErrors() === false ) {
						$transaction = new Budget_DataModel_TransactionDM();
						$transaction->setToCategory($category->getCategoryId());
						$transaction->setFromAccount($parent_account->getAccountId());
						$transaction->setOwnerId($this->session->userdata("user_id"));
						$transaction->setTransactionAmount($requested_amount);
						$transaction->setTransactionDate($date);
						$transaction->setTransactionInfo("Funds distributed from ".$parent_account->getAccountName()." account to ".$category->getCategoryName());
						$transaction->saveTransaction();
					}

					$parent_account->transactionEnd();
					break;

				case 'refund':
					$transaction_info = ($this->input->post('refundId')) ? "Refund on transaction id: ".$this->input->post('refundId') : "Refund";
					$category->setCurrentAmount($category->getCurrentAmount() + $requested_amount);

					$category->saveCategory();

					if( $category->isErrors() === false ) {
						$transaction = new Budget_DataModel_TransactionDM();
						$transaction->setToCategory($category->getCategoryId());
						$transaction->setOwnerId($this->session->userdata("user_id"));
						$transaction->setTransactionAmount($requested_amount);
						$transaction->setTransactionDate($date);
						$transaction->setTransactionInfo($transaction_info);
						$transaction->saveTransaction();
					}
					break;

				case 'deduction':
				default:
					$category->transactionStart();
					$transaction_info = ($this->input->post("description")) ? $this->input->post("description") : "Deduction";
					$category->setCurrentAmount($category->getCurrentAmount() - $requested_amount);

					$category->saveCategory();

					if( $category->isErrors() === false ) {
						$transaction = new Budget_DataModel_TransactionDM();
						$transaction->setFromCategory($category->getCategoryId());
						$transaction->setOwnerId($this->session->userdata("user_id"));
						$transaction->setTransactionAmount($requested_amount);
						$transaction->setTransactionDate($date);
						$transaction->setTransactionInfo($transaction_info);
						$transaction->saveTransaction();
					}
					$category->transactionEnd();
					break;
			}

			redirect("/book/getBookInfo/".$this->input->post('id'));
		} catch(Exception $e) {
			show_error("There was a problem saving the change.", 500);
			log_error('error', $e->getMessage());
		}
	}

    /**
     * deletes a transaction returning the value of the transaction to the parent account of the category
     * in which the transaction lived.
     * @todo - look into making this transactional so if we fail to delete the transaction we don't go out of balance on the
     * accounts and categories.
     *
     * @param int $transaction_id
     */
    public function deleteTransaction($transaction_id) {
        $transaction = new Budget_DataModel_TransactionDM($transaction_id);

        if($transaction->getFromAccount()) {
            //undo an account to category deposit
            if($this->removeFundsFromCategory($transaction)) {
                $result = $this->returnFundsToAccount($transaction);
            }
        } elseif($transaction->getFromCategory() && !$transaction->getToCategory()) {
            //undo a category deduction
            $result = $this->returnFundsToCategory($transaction);
        } elseif($transaction->getFromCategory() && $transaction->getToCategory()) {
            //undo a category to category transfer
            if($this->removeFundsFromCategory($transaction)) {
                $result = $this->returnFundsToCategory($transaction);
            }
        } else {
            //undo a category refund/deposit
            $result = $this->removeFundsFromCategory($transaction);
        }

        if($result === true) {
            $transaction->deleteTransaction();
        }

        redirect('/'.$this->input->post('return_uri'));
    }

    /**
     * remove funds from a category
     *
     * @param Budget_DataModel_TransactionDM $transaction
     * @return type
     */
    private function removeFundsFromCategory(Budget_DataModel_TransactionDM $transaction) {
		$category     = new Budget_DataModel_CategoryDM($transaction->getToCategory(), $this->session->userdata('user_id'));
		$new_cat_amt  = subtract($category->getCurrentAmount(), $transaction->getTransactionAmount(), 2);
		$category->setCurrentAmount($new_cat_amt);

		return $category->saveCategory();
    }

    /**
     * take funds from a transaction and put them back into the category
     *
     * @param Budget_DataModel_TransactionDM $transaction
     */
    private function returnFundsToCategory(Budget_DataModel_TransactionDM $transaction) {
        $category = new Budget_DataModel_CategoryDM($transaction->getFromCategory(), $this->session->userdata('user_id'));
        $new_amt  = add($category->getCurrentAmount(), $transaction->getTransactionAmount(), 2);
        $category->setCurrentAmount($new_amt);
        return $category->saveCategory();
    }

    /**
     * takes funds from a category/transaction and put them back into an account
     *
     * @param Budget_DataModel_TransactionDM $transaction
     */
    private function returnFundsToAccount(Budget_DataModel_TransactionDM $transaction) {
        $account      = new Budget_DataModel_AccountDM($transaction->getFromAccount(), $this->session->userdata('user_id'));
        $new_acct_amt = add($account->getAccountAmount(), $transaction->getTransactionAmount(), 2);
        $account->setAccountAmount($new_acct_amt);
        return $account->saveAccount();
    }
}
