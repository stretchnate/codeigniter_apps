<?php
require_once APPPATH.'libraries/Traits/Distribute.php';

class Funds extends N8_Controller {

    use \Traits\Distribute;

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

		$accounts_model = new Accounts();

		$funds_data['accounts'] = $accounts_model->getAccounts($this->session->userdata("user_id"));

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
            $handler->addDeposit($account_dm, $amount, $this->input->post('source', true), new \DateTime($this->input->post('date', true)), true);

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
		try {
            $account_dm = new \Budget_DataModel_AccountDM($this->input->post("account"), $this->session->userdata('user_id'));
            $account_dm->orderCategoriesByDueFirst($this->input->post('date'));
            $amount = $this->input->post('net') ? $this->input->post('net') : $this->input->post('gross');
            $date = $this->input->post('date') ? new \DateTime($this->input->post('date', true)) : new DateTime();

            $handler = new \Deposit\Handler($this->session->userdata('user_id'));
            $handler->addDeposit($account_dm, $amount, $this->input->post('source', true), $date, false);

            $this->distribute($account_dm);

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

			switch($this->input->post('operation')) {
				case 'distribution':
					$from = null;
					$refund = null;
					$deposit_fields = new \Deposit\Row\Fields();
					$deposit_fields->setId($this->input->post('deposit_id'));
					$deposit = new \Deposit\Row($deposit_fields);

					if($deposit->getFields()->getRemaining() < $requested_amount) {
						$requested_amount = $deposit->getFields()->getRemaining();
					}

					$handler = new \Deposit\Handler($this->session->user_id);
					$handler->distributeFunds($parent_account, $category, $deposit, $requested_amount, $date);
					break;

				case 'refund':
					$transaction_info = ($this->input->post('refundId')) ? "Refund on transaction id: ".$this->input->post('refundId') : "Refund";
					$category->setCurrentAmount(add($category->getCurrentAmount(), $requested_amount, 2));

					$category->saveCategory();

					if( $category->isErrors() === false ) {
						$transaction = new \Transaction\Row();
						$transaction->getStructure()->setToCategory($category->getCategoryId());
						$transaction->getStructure()->setOwnerId($this->session->userdata("user_id"));
						$transaction->getStructure()->setTransactionAmount($requested_amount);
						$transaction->getStructure()->setTransactionDate($date);
						$transaction->getStructure()->setTransactionInfo($transaction_info);
						$transaction->saveTransaction();
					}
					break;

				case 'deduction':
				default:
					$category->transactionStart();
					$transaction_info = ($this->input->post("description")) ? $this->input->post("description") : "Deduction";
					$category->setCurrentAmount(subtract($category->getCurrentAmount(), $requested_amount, 2));

					$category->saveCategory();

					if( $category->isErrors() === false ) {
						$transaction = new \Transaction\Row();
						$transaction->getStructure()->setFromCategory($category->getCategoryId());
						$transaction->getStructure()->setOwnerId($this->session->userdata("user_id"));
						$transaction->getStructure()->setTransactionAmount($requested_amount);
						$transaction->getStructure()->setTransactionDate($date);
						$transaction->getStructure()->setTransactionInfo($transaction_info);
						$transaction->saveTransaction();
					}
					$category->transactionEnd();
					break;
			}

			redirect("/book/getCategory/".$this->input->post('id'));
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
     *
     * @param int $transaction_id
     */
    public function deleteTransaction($transaction_id) {
        try {
            $transaction = new \Transaction\Row($transaction_id);

            if($this->undoTransaction($transaction) === true) {
                $transaction->deleteTransaction();
            }

            redirect('/' . $this->input->post('return_uri'));
        } catch(Exception $e) {
            $result = new stdClass();
            $result->success = true;
            log_error($e->getMessage());

            echo json_encode($result);
        }
    }

    /**
     * @param \Transaction\Row $transaction
     * @return bool|type
     * @throws Exception
     */
    private function undoTransaction(\Transaction\Row $transaction) {
        $result = false;
        if($transaction->getStructure()->getFromAccount()) {
            //undo an account to category deposit
            if($this->removeFundsFromCategory($transaction)) {
                $result = $this->returnFundsToDeposit($transaction);
            }
        } elseif($transaction->getStructure()->getFromCategory() && !$transaction->getStructure()->getToCategory()) {
            //undo a category deduction
            $result = $this->returnFundsToCategory($transaction);
        } elseif($transaction->getStructure()->getFromCategory() && $transaction->getStructure()->getToCategory()) {
            //undo a category to category transfer
            if($this->removeFundsFromCategory($transaction)) {
                $result = $this->returnFundsToCategory($transaction);
            }
        } elseif($transaction->getStructure()->getToAccount()) {
            //undo a deposit
            $this->undoDeposit($transaction);
            $result = true;
        } else {
            //undo a category refund/deposit
            $result = $this->removeFundsFromCategory($transaction);
        }

        return $result;
    }

    /**
     * undo a deposit and all of it's distributions
     *
     * @param \Transaction\Row $deposit_transaction
     * @throws Exception
     */
    private function undoDeposit(\Transaction\Row $deposit_transaction) {
        $trans_fields = new \Transaction\Fields();
        $trans_fields->setDepositId($deposit_transaction->getStructure()->getDepositId());
        $trans_fields->setFromAccount($deposit_transaction->getStructure()->getToAccount());
        $transactions = new TransactionIterator($trans_fields);
        while($transactions->valid()) {
            if($this->undoTransaction($transactions->current())) {
                $transactions->current()->deleteTransaction();
            }
            $transactions->next();
        }

        $fields = new \Deposit\Row\Fields();
        $fields->setId($deposit_transaction->getStructure()->getDepositId());
        $deposit = new \Deposit\Row($fields);
        $deposit->delete();
    }

    /**
     * remove funds from a category
     *
     * @param Row $transaction
     * @return type
     */
    private function removeFundsFromCategory(\Transaction\Row $transaction) {
		$category     = new Budget_DataModel_CategoryDM($transaction->getStructure()->getToCategory(), $this->session->userdata('user_id'));
		$new_cat_amt  = subtract($category->getCurrentAmount(), $transaction->getStructure()->getTransactionAmount(), 2);
		$category->setCurrentAmount($new_cat_amt);

		return $category->saveCategory();
    }

    /**
     * take funds from a transaction and put them back into the category
     *
     * @param Row $transaction
     * @return bool
     */
    private function returnFundsToCategory(\Transaction\Row $transaction) {
        $category = new Budget_DataModel_CategoryDM($transaction->getStructure()->getFromCategory(), $this->session->userdata('user_id'));
        $new_amt  = add($category->getCurrentAmount(), $transaction->getStructure()->getTransactionAmount(), 2);
        $category->setCurrentAmount($new_amt);
        return $category->saveCategory();
    }

    /**
     * takes funds from a category/transaction and put them back into a deposit
     *
     * @param Row $transaction
     * @return bool
     */
    private function returnFundsToDeposit(\Transaction\Row $transaction) {
        $fields = new \Deposit\Row\Fields();
        $fields->setId($transaction->getStructure()->getDepositId());
        $deposit = new \Deposit\Row($fields);
        $deposit->getFields()->setRemaining(add($deposit->getFields()->getRemaining(), $transaction->getStructure()->getTransactionAmount(), 2));

        return $deposit->save();
    }
}
