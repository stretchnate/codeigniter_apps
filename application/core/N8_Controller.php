<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class N8_Controller extends CI_Controller {

    protected $view;

	function __construct() {
		parent::__construct();
	}

	/**
	 * form validation method
	 */
	function validate(&$rules) {
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules($rules);

		return $this->form_validation->run();
	}

	/**
	 * Get detailed transaction info for the specified transactions
	 *
	 * @param Array        $transactions
	 * @param String/Int   $specified_transaction
     * @return \Transaction\Row
     * @throws Exception
	 */
	protected function transactionDetails($transactions, $specified_transaction) {
		$transaction_details = new \Transaction\Row();

		if (strtolower($specified_transaction) == "last") {
			$specified_transaction = count($transactions) - 1;
		}

		$transaction = new \Transaction\Row($transactions[$specified_transaction]->transaction_id);

		if ($transaction->getStructure()->getToCategory()) {
			$to_category = new Budget_DataModel_CategoryDM($transaction->getStructure()->getToCategory(), $this->session->userdata('user_id'));

			$transaction_details->getStructure()->setToCategory($to_category->getCategoryId());
			$transaction_details->setToCategoryName($to_category->getCategoryName());
		}

		if ($transaction->getStructure()->getFromCategory()) {
			$from_category = new Budget_DataModel_CategoryDM($transaction->getStructure()->getFromCategory(), $this->session->userdata('user_id'));

			$transaction_details->getStructure()->setFromCategory($from_category->getCategoryId());
			$transaction_details->setFromCategoryName($from_category->getCategoryName());
		}

		if ($transaction->getStructure()->getToAccount()) {
			$to_account = new Budget_DataModel_AccountDM($transaction->getStructure()->getToAccount(), $this->session->userdata('user_id'));

			$transaction_details->getStructure()->setToAccount($to_account->getAccountId());
			$transaction_details->setToAccountName($to_account->getAccountName());
		}

		if ($transaction->getStructure()->getFromAccount()) {
			$from_account = new Budget_DataModel_AccountDM($transaction->getStructure()->getFromAccount(), $this->session->userdata('user_id'));

			$transaction_details->getStructure()->setFromAccount($from_account->getAccountId());
			$transaction_details->setFromAccountName($from_account->getAccountName());
		}


		$transaction_details->getStructure()->setDepositId($transaction->getStructure()->getDepositId());
		$transaction_details->getStructure()->setOwnerId($transaction->getStructure()->getOwnerId());
		$transaction_details->getStructure()->setTransactionAmount($transaction->getStructure()->getTransactionAmount());
		$transaction_details->getStructure()->setTransactionDate($transaction->getStructure()->getTransactionDate());
		$transaction_details->getStructure()->setTransactionInfo($transaction->getStructure()->getTransactionInfo());

		return $transaction_details;
	}

	/**
	 * Accepts a TransactionDM Object and returns a user friendly sentence about the transaction
	 *
	 * @param Object $transaction
	 * @return String
	 */
	protected function UserFriendlyTransactionDetails($transaction) {

		switch ($transaction->getTransactionType()) {
			//account to category deposits
			case "account_to_category_deposit":
				$return = "Deposit from {$transaction->getFromAccountName()} to <a href='/book/getCategory/{$transaction->getStructure()->getToCategory()}'>{$transaction->getToCategoryName()}</a> for $" . number_format($transaction->getStructure()->getTransactionAmount(), 2, '.', ',');
				break;

			//category to category transfers
			case "category_to_category_transfer":
				$return = "Transfer from <a href='/book/getCategory/{$transaction->getStructure()->getFromCategory()}'>{$transaction->getFromCategoryName()}</a> to <a href='/book/getCategory/{$transaction->getStructure()->getToCategory()}'>{$transaction->getToCategoryName()}</a> for $" . number_format($transaction->getStructure()->getTransactionAmount(), 2, '.', ',');
				break;

			//account to account transfers
			case "account_to_account_transfer":
				$return = "Transfer from {$transaction->getFromAccountName()} to {$transaction->getToAccountName()} for $" . number_format($transaction->getStructure()->getTransactionAmount(), 2, '.', ',');
				break;

			//deposits
			case "deposit":
				$return = "Deposit (deposit id: {$transaction->getStructure()->getDepositId()}) into {$transaction->getToAccountName()} for $" . number_format($transaction->getStructure()->getTransactionAmount(), 2, '.', ',');
				break;

			//deductions
			case "deduction":
				$return = "Deduction from <a href='/book/getCategory/{$transaction->getStructure()->getFromCategory()}'>{$transaction->getFromCategoryName()}</a> for $" . number_format($transaction->getStructure()->getTransactionAmount(), 2, '.', ',');
				break;

			//refunds
			case "refund":
				$return = "Refund to <a href='/book/getCategory/{$transaction->getStructure()->getToCategory()}'>{$transaction->getToCategoryName()}</a> for $" . number_format($transaction->getStructure()->getTransactionAmount(), 2, '.', ',');
				break;

			default:
				$return = false;
				break;
		}

		return $return;
	}
}
