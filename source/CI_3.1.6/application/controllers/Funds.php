<?php
class Funds extends N8_Controller {

    /**
     * constructor
     */
	function __construct() {
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
        try {
            $account_dm = new \Budget_DataModel_AccountDM($this->input->post("account"), $this->session->userdata('user_id'));
            $amount = $this->input->post('net') ? $this->input->post('net') : $this->input->post('gross');

            $handler = new \Deposit\Handler($this->session->userdata('user_id'));
            $handler->addDeposit($account_dm, $amount, $this->input->post('source', true), $this->input->post('date', true), false);

            header("Location: /");
        } catch(\Exception $e) {
            log_message('error', $e->getMessage());
            show_error('There was a problem processing your request.', 500);
        }
	}


	/**
	 * Adds funds to each account automatically
	 *
	 */
	public function automaticallyDistributeFunds() {
		$this->auth->restrict();

		try {
            $account_dm = new \Budget_DataModel_AccountDM($this->input->post("account"), $this->session->userdata('user_id'));
            $amount = $this->input->post('net') ? $this->input->post('net') : $this->input->post('gross');

            $handler = new \Deposit\Handler($this->session->userdata('user_id'));
            $handler->addDeposit($account_dm, $amount, $this->input->post('source', true), $this->input->post('date', true));

            header("Location: /");
        } catch(\Exception $e) {
            log_message('error', $e->getMessage());
            show_error('There was a problem processing your request.', 500);
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
	function addDepositToAccount($account, $owner_id, $date, $net, $gross, $source) {
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
	 * set account amount to 0 (does not distribute any funds)
	 */
	public function ajaxClearAccount() {
		$result = new stdClass();
		$result->success = true;
		try {
			$account = new Budget_DataModel_AccountDM($this->input->post('account_id', true), $this->session->userdata('user_id'));
			$account->setAccountAmount(0);
			$account->saveAccount();
		} catch(Exception $e) {
			$result->success = false;
			log_error('error', $e->getMessage());
		}

		echo json_encode($result);
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
