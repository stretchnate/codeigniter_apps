<?php

class Maintenance extends N8_Controller {

	private $transaction_map = array();

	function __construct() {
		parent::__construct();
	}

	function index() {
		set_time_limit(0);
		$this->load->model("maintenanceMDL", "MAIN", TRUE);
		$transactions = $this->MAIN->getTransactions();
		$new_transaction = false;

		foreach($transactions as $transaction) {

			if(!$new_transaction || $transaction->bookTransAmt != $new_transaction->getTransactionAmount()) {
				$new_transaction = new Budget_DataModel_TransactionDM();
			}

			if($transaction->TransType == 'a') {
				if( preg_match("/B/", $transaction->bookId) ) {
					$new_transaction->setToAccount($transaction->bookId);
				} else {
					$new_transaction->setToCategory($transaction->bookId);
				}
			} else {
				if( preg_match("/B/", $transaction->bookId) ) {
					$new_transaction->setFromAccount($transaction->bookId);
				} else {
					$new_transaction->setFromCategory($transaction->bookId);
				}
			}

			$date = date("Y-m-d H:i:s");
			if( !preg_match("/0000-00-00/", $transaction->bookTransDate) && !empty($transaction->bookTransDate)) {
				$date = date("Y-m-d H:i:s", strtotime($transaction->bookTransDate));
			}

			$new_transaction->setOwnerId($transaction->ownerId);
			$new_transaction->setTransactionAmount($transaction->bookTransAmt);
			$new_transaction->setTransactionDate($date);
			$new_transaction->setTransactionInfo($transaction->bookTransPlace);

			$add_transaction = false;
			if( preg_match("/(Bucket|Transfer)/", $transaction->bookTransPlace) || preg_match("/B/", $transaction->bookId) ) {
				if($new_transaction->getToCategory()
					&& ($new_transaction->getFromAccount() || $new_transaction->getFromCategory()) ) {

					$add_transaction = true;
				}
			} else {
				$add_transaction = true;
			}

			if($add_transaction === true) {
				echo "***adding transaction ".$transaction->transactionId."***<br />";
				$new_transaction->saveTransaction();

				//if we get an error on the first attempt, try to fix it.
				if( count($new_transaction->getErrors()) > 0) {
					echo "first attempt failed, attempting repair<br />";
					if($new_transaction->getToCategory() == $new_transaction->getFromCategory() && $new_transaction->getFromAccount()) {
						$new_transaction->setFromCategory("");
					}

					if($new_transaction->getToCategory() != $new_transaction->getFromCategory() && $new_transaction->getFromAccount()) {
						$new_transaction->setFromAccount("");
					}

					$new_transaction->setErrors();
					$new_transaction->saveTransaction();

					if( count($new_transaction->getErrors()) > 0) {
						echo "second attempt failed for transaction ".$transaction->transactionId."<br />";
						echo "<pre>".print_r($new_transaction->getErrors(), true)."</pre>";
					}
					$new_transaction = false;
				} else {
					$this->transaction_map[$transaction->transactionId] = $new_transaction->getInsertId();
					$new_transaction = false;
				}
			}
		}
		$this->updateClearedTransactions();
	}

	function updateClearedTransactions() {
		$this->load->model("maintenanceMDL", "MAIN", TRUE);
		foreach($this->transaction_map as $old_id => $new_id) {
			echo "updating $old_id to $new_id in cleared_transactions<br />";
			$this->MAIN->updateClearedTransactions($old_id, $new_id);
		}
	}
}
?>
