<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class N8_Controller extends CI_Controller {

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
	 */
	protected function transactionDetails($transactions, $specified_transaction) {
		$transaction_details = new Budget_DataModel_TransactionDM();

		if( strtolower($specified_transaction) == "last" ) {
			$specified_transaction = count($transactions) - 1;
		}

		$transaction = new Budget_DataModel_TransactionDM( $transactions[$specified_transaction]->transaction_id );

		if($transaction->getToCategory()) {
			$to_category = new Budget_DataModel_CategoryDM($transaction->getToCategory());

			$transaction_details->setToCategory($to_category->getCategoryId());
			$transaction_details->setToCategoryName($to_category->getCategoryName());
		}

		if($transaction->getFromCategory()) {
			$from_category = new Budget_DataModel_CategoryDM($transaction->getFromCategory());

			$transaction_details->setFromCategory( $from_category->getCategoryId() );
			$transaction_details->setFromCategoryName( $from_category->getCategoryName() );
		}

		if($transaction->getToAccount()) {
			$to_account = new Budget_DataModel_AccountDM($transaction->getToAccount());

			$transaction_details->setToAccount($to_account->getAccountId() );
			$transaction_details->setToAccountName($to_account->getAccountName() );
		}

		if($transaction->getFromAccount()) {
			$from_account = new Budget_DataModel_AccountDM($transaction->getFromAccount());

			$transaction_details->setFromAccount($from_account->getAccountId() );
			$transaction_details->setFromAccountName($from_account->getAccountName() );
		}


		$transaction_details->setDepositId(        $transaction->getDepositId()          );
		$transaction_details->setOwnerId(          $transaction->getOwnerId()            );
		$transaction_details->setTransactionAmount($transaction->getTransactionAmount()  );
		$transaction_details->setTransactionDate(  $transaction->getTransactionDate()    );
		$transaction_details->setTransactionInfo(  $transaction->getTransactionInfo()    );

		return $transaction_details;
	}

	/**
	 * Accepts a TransactionDM Object and returns a user friendly sentence about the transaction
	 *
	 * @param Object $transaction
	 * @return String
	 */
	protected function UserFriendlyTransactionDetails($transaction) {

		switch($transaction->getTransactionType()) {
			//account to category deposits
			case "account_to_category_deposit":
				$return = "Deposit from {$transaction->getFromAccountName()} to <a href='/book/getBookInfo/{$transaction->getToCategory()}'>{$transaction->getToCategoryName()}</a> for $".number_format($transaction->getTransactionAmount(), 2, '.', ',');
				break;

			//category to category transfers
			case "category_to_category_transfer":
				$return = "Transfer from <a href='/book/getBookInfo/{$transaction->getFromCategory()}'>{$transaction->getFromCategoryName()}</a> to <a href='/book/getBookInfo/{$transaction->getToCategory()}'>{$transaction->getToCategoryName()}</a> for $".number_format($transaction->getTransactionAmount(), 2, '.', ',');
				break;

			//account to account transfers
			case "account_to_account_transfer":
				$return = "Transfer from {$transaction->getFromAccountName()} to {$transaction->getToAccountName()} for $".number_format($transaction->getTransactionAmount(), 2, '.', ',');
				break;

			//deposits
			case "deposit":
				$return = "Deposit (deposit id: {$transaction->getDepositId()}) into {$transaction->getToAccount()} for $".number_format($transaction->getTransactionAmount(), 2, '.', ',');
				break;

			//deductions
			case "deduction":
				$return = "Deduction from <a href='/book/getBookInfo/{$transaction->getFromCategory()}'>{$transaction->getFromCategoryName()}</a> for $".number_format($transaction->getTransactionAmount(), 2, '.', ',');
				break;

			//refunds
			case "refund":
				$return = "Refund to <a href='/book/getBookInfo/{$transaction->getToCategory()}'>{$transaction->getToCategoryName()}</a> for $".number_format($transaction->getTransactionAmount(), 2, '.', ',');
				break;

			default:
				$return = false;
				break;
		}

		return $return;
	}
}
