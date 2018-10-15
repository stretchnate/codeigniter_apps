<?php
class fundsTransferCTL extends N8_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('account');
	}

	/**
	 * Transfer funds between bank accounts, currently doesn't log the transfer.
	 *
	 * @since 2012.03.15
	 */
	function transferAccounts() {
		$this->auth->restrict();

		$this->load->model("accounts", "ACCT", TRUE);
		$errors = array();

		$from = $this->input->post("from-accounts");
		$to   = $this->input->post("to-accounts");
		$transfer_amount = (float)$this->input->post("amount");

		if(!$from) {
			$errors[] = "Please select a from account";
		}

		if(!$to) {
			$errors[] = "Please select a to account";
		}

		if($from == $to) {
			$errors[] = "From and To accounts are the same";
		}

		if(count($errors) < 1) {
			$from_account    = $this->ACCT->getAccount($from);
			$to_account      = $this->ACCT->getAccount($to);

			if($from_account->account_amount < $transfer_amount) {
				$transfer_amount = $from_account->account_amount;
				$errors[] = "I emptied {$from_account->account_name} but it wasn't enough :(";
			}

			$from_account->account_amount = (float)$from_account->account_amount - $transfer_amount;
			$to_account->account_amount = (float)$to_account->account_amount + $transfer_amount;

			$this->ACCT->transactionStart();//begin transaction

			$this->ACCT->saveAccount($from_account);
			$this->ACCT->saveAccount($to_account);

			//add the transaction
			$transaction = new Transaction();
			$transaction->getStructure()->setToAccount($to_account->account_id);
			$transaction->getStructure()->setFromAccount($from_account->account_id);
			$transaction->getStructure()->setOwnerId($this->session->userdata("user_id"));
			$transaction->getStructure()->setTransactionAmount($transfer_amount);
			$transaction->getStructure()->setTransactionDate( date("Y-m-d H:i:s") );
			$transaction->getStructure()->setTransactionInfo("Transfer from ".$from_account->account_name." to ".$to_account->account_name);
			$transaction->saveTransaction();

			$this->ACCT->transactionEnd();//end transaction
		}

		$this->transferFundsView($errors);
	}

	function transferCategories() {
		$this->auth->restrict();

		try {
			$errors = array();

			$from   = new Budget_DataModel_CategoryDM($this->input->post('from'), $this->session->userdata('user_id'));
			$to     = new Budget_DataModel_CategoryDM($this->input->post('to'), $this->session->userdata('user_id'));

			$amount = (float)$this->input->post('amount');

			if($amount > $from->getCurrentAmount()) {
				$amount = $from->getCurrentAmount();
				$errors[] = "Unable to transfer total requested amount, insufficient funds in {$from->getCategoryName()}";
			}

			if( count($errors) < 1 ) {
				$from->transactionStart();

				$from->setCurrentAmount($from->getCurrentAmount() - $amount);
				$from->saveCategory();
				if( count($from->getErrors()) < 1 ) {
					$to->setCurrentAmount($to->getCurrentAmount() + $amount);
					$to->saveCategory();
					if( count($to->getErrors()) < 1 ) {
						//add the transaction
						$transaction = new Transaction();
						$transaction->getStructure()->setToCategory($to->getCategoryId());
						$transaction->getStructure()->setFromCategory($from->getCategoryId());
						$transaction->getStructure()->setOwnerId($this->session->userdata("user_id"));
						$transaction->getStructure()->setTransactionAmount($amount);
						$transaction->getStructure()->setTransactionDate( date("Y-m-d H:i:s") );
						$transaction->getStructure()->setTransactionInfo("Transfer from ".$from->getCategoryName()." to ".$to->getCategoryName());
						$transaction->saveTransaction();

						if( count($transaction->getErrors()) > 0 ) {
							$errors[] = "Transaction failed, unable to store record";
						}
					} else {
						$errors[] = "woops, unable to add funds to {$to->getCategoryName()}...aborting";
					}
				} else {
					$errors[] = "woops, unable to extract funds from {$from->getCategoryName()}...aborting.";
				}
				$from->transactionEnd();
			}
			$this->transferFundsView($errors);
		} catch(Exception $e) {
			show_error("There was a problem preforming this action.", 500);
			log_error('error', $e->getMessage());
		}
	}

	function transferFundsView($errors = array()) {
		$this->auth->restrict();

		$this->load->model("accounts", "ACCT", TRUE);

		$data['accounts'] = $this->ACCT->getAccountsAndDistributableCategories($this->session->userdata('user_id'));

		// $transactions['transactions'] = $this->ACCT->getUserTransactions(null, $this->session->userdata('user_id'));
		$t_grid = new TransactionsGrid();
		$t_grid->run();

		$transactions["transactions"] = $t_grid->getTransactionsGrid();

		$props['youAreHere'] = "Transfer Funds";
		$props['scripts'] = $this->jsincludes->transferFunds();
		$props['title'] = "Transfer Funds";
		$props['links'] = $this->utilities->createLinks('main_nav');
		$props['errors'] = $errors;
		$props['logged_user'] = $this->session->userdata('logged_user');

		$this->load->view('header', $props);
		$this->load->view('transferFunds', $data);
		$this->load->view('transactions', $transactions);
		$this->load->view('footer');
	}
}

?>
