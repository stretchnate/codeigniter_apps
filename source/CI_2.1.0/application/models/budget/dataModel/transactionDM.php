<?php

class Budget_DataModel_TransactionDM extends N8_Model {

	private $transaction_id;
	private $to_category        = '';
	private $from_category      = '';
	private $to_account         = '';
	private $from_account       = '';
	private $deposit_id         = '';
	private $owner_id           = '';
	private $transaction_amount = '';
	private $transaction_date   = '';
	private $transaction_info   = '';
	private $insert_id;

	//these are not stored in the db on transactions.
	private $to_category_name;
	private $from_category_name;
	private $to_account_name;
	private $from_account_name;

	function __construct($transaction_id = null) {
		parent::__construct();

		if($transaction_id) {
			$this->loadTransaction($transaction_id);
		}
	}

	/**
	 * fetches the transaction from the db and loads the data into the class properties.
	 *
	 * @param transaction_id Int
	 * @return void
	 */
	public function loadTransaction(&$transaction_id) {
		$query = $this->db->get_where("transactions", array("transaction_id" => $transaction_id));

		foreach($query->row() as $column => $value) {
			if( property_exists("Budget_DataModel_TransactionDM", $column) ) {

				$this->$column = trim($value);
			}
		}
	}

	/**
	 * saves the properties into the transaction table
	 *
	 * @return void
	 */
	public function saveTransaction() {

		if($this->validateTransaction()) {

			if($this->transaction_id > 0) {
				$this->updateTransaction();
			} else {
				$insert = $this->insertTransaction();
				$this->insert_id = $this->db->insert_id();
			}
			$this->auth->updateLoginHistory(TRUE);
		}
	}

	/**
	 * updates and existing transaction
	 *
	 * @return Bool
	 */
	private function updateTransaction() {
		$sets = array();
		if($this->to_category) {
			$sets["to_category"]    = $this->to_category;
		}

		if($this->from_category) {
			$sets["from_category"]  = $this->from_category;
		}

		if($this->to_account) {
			$sets["to_account"]     = $this->to_account;
		}

		if($this->from_account) {
			$sets["from_account"]   = $this->from_account;
		}

		if($this->deposit_id) {
			$sets["deposit_id"]     = $this->deposit_id;
		}

		$sets["transaction_amount"] = $this->dbNumberFormat($this->transaction_amount);
		$sets["transaction_date"]   = $this->transaction_date;
		$sets["transaction_info"]   = $this->transaction_info;

		if($this->db->where("transaction_id", $this->transaction_id)->update("transactions", $sets)) {
			return true;
		}
		return false;
	}

	/**
	 * inserts a new transaction
	 *
	 * @return Int
	 */
	private function insertTransaction() {
		$values = array();

		if($this->to_category) {
			$values["to_category"]    = $this->to_category;
		}

		if($this->from_category) {
			$values["from_category"]  = $this->from_category;
		}

		if($this->to_account) {
			$values["to_account"]     = $this->to_account;
		}

		if($this->from_account) {
			$values["from_account"]   = $this->from_account;
		}

		if($this->deposit_id) {
			$values["deposit_id"]     = $this->deposit_id;
		}

		$values["owner_id"]           = $this->owner_id;
		$values["transaction_amount"] = $this->dbNumberFormat($this->transaction_amount);
		$values["transaction_date"]   = $this->transaction_date;
		$values["transaction_info"]   = $this->transaction_info;

		return $this->db->insert("transactions", $values);
	}

    /**
     * deletes a transaction
     *
     * @return type
     */
    public function deleteTransaction() {
        if(!empty($this->transaction_id)) {
            return $this->db->delete('transactions', array('transaction_id' => $this->transaction_id));
        }
    }

	/**
	 * validates the transaction to ensure proper relationships between accounts, categories and deposits are satisfied.
	 * to_category can have a from_category or from_account
	 * from_category can have a to_category only
	 * from_account can have a to_account or to_category
	 * to_account can have a from_account or deposit_id
	 * deposit_id can have a to_account only
	 *
	 * @return bool
	 */
	private function validateTransaction() {
		$return = false;
		//there must be an owner for every transaction
		if( !$this->owner_id ) {
			$this->setError("No Owner ID for transaction");
		}

		$return = $this->getTransactionType();

		if($return === false) {
			$this->setError("Invalid Combination for transaction\nfrom account = ".$this->from_account . "to account = ".$this->to_account .
							"from category = ".$this->from_category .
							"to category = ".$this->to_category .
							"deposit id = ".$this->deposit_id);
		}
		return $return;
	}

	public function getTransactionType() {
		$return = false;

		//account to category deposits
		if($this->isAccountToCategoryDeposit() === true) {
			$return = "account_to_category_deposit";
		}

		//category to category transfers
		else if($this->isCategoryToCategoryTransfer() === true) {
			$return = "category_to_category_transfer";
		}

		//account to account transfers
		else if($this->isAccountToAccountTransfer() === true) {
			$return = "account_to_account_transfer";
		}

		//deposits
		else if($this->isDeposit() === true) {
			$return = "deposit";
		}

		//deductions or refunds
		else if($this->isDeduction() === true) {
			$return = "deduction";
		}

		else if($this->isRefund() === true) {
			$return = "refund";
		}

		return $return;
	}

	//account to category deposits
	protected function isAccountToCategoryDeposit() {
		if($this->getFromAccount() && $this->getToCategory()) {
			if( !$this->getToAccount() && !$this->getFromCategory() && !$this->getDepositId() ) {
				return true;
			}
		}
		return false;
	}

	//category to category transfers
	protected function isCategoryToCategoryTransfer() {
		if($this->getFromCategory() && $this->getToCategory()) {
			if( !$this->getToAccount() && !$this->getFromAccount() && !$this->getDepositId() ) {
				return true;
			}
		}
		return false;
	}

	//account to account transfers
	protected function isAccountToAccountTransfer() {
		if($this->getFromAccount() && $this->getToAccount()) {
			if( !$this->getToCategory() && !$this->getFromCategory() && !$this->getDepositId() ) {
				return true;
			}
		}
		return false;
	}

	//deposits
	protected function isDeposit() {
		if($this->getDepositId() && $this->getToAccount()) {
			if( !$this->getToCategory() && !$this->getFromCategory() && !$this->getFromAccount() ) {
				return true;
			}
		}
		return false;
	}

	//deductions
	protected function isDeduction() {
		if($this->getFromCategory()) {
			if( !$this->getToCategory() && !$this->getToAccount() && !$this->getDepositId() && !$this->getFromAccount() ) {
				return true;
			}
		}
		return false;
	}

	//refunds
	protected function isRefund() {
		if($this->getToCategory()) {
			if( !$this->getFromCategory() && !$this->getToAccount() && !$this->getDepositId() && !$this->getFromAccount() ) {
				return true;
			}
		}
		return false;
	}

	/** *********************************************
	 * Getters and Setters
	 ************************************************/
	public function getTransactionId() {
		return $this->transaction_id;
	}

	public function setTransactionId($transaction_id) {
		$this->transaction_id = $transaction_id;
	}

	public function getToCategory() {
		return $this->to_category;
	}

	public function setToCategory($to_category) {
		$this->to_category = $to_category;
	}

	public function getFromCategory() {
		return $this->from_category;
	}

	public function setFromCategory($from_category) {
		$this->from_category = $from_category;
	}

	public function getToAccount() {
		return $this->to_account;
	}

	public function setToAccount($to_account) {
		$this->to_account = $to_account;
	}

	public function getFromAccount() {
		return $this->from_account;
	}

	public function setFromAccount($from_account) {
		$this->from_account = $from_account;
	}

	public function getDepositId() {
		return $this->deposit_id;
	}

	public function setDepositId($deposit_id) {
		$this->deposit_id = $deposit_id;
	}

	public function getOwnerId() {
		return $this->owner_id;
	}

	public function setOwnerId($owner_id) {
		$this->owner_id = $owner_id;
	}

	public function getTransactionAmount() {
		return $this->transaction_amount;
	}

	public function setTransactionAmount($transaction_amount) {
		$this->transaction_amount = $transaction_amount;
	}

	public function getTransactionDate() {
		return $this->transaction_date;
	}

	public function setTransactionDate($transaction_date) {
		$pattern = "/[\/.-]/";

		if( preg_match($pattern, $transaction_date) ) {
			$transaction_date = strtotime($transaction_date);
		}

		$this->transaction_date = date("Y-m-d H:i:s", $transaction_date);
	}

	public function getTransactionInfo() {
		return $this->transaction_info;
	}

	public function setTransactionInfo($transaction_info) {
		$this->transaction_info = $transaction_info;
	}

	public function getInsertId() {
		return $this->insert_id;
	}

	public function getToCategoryName() {
		return $this->to_category_name;
	}

	public function setToCategoryName($to_category_name) {
		$this->to_category_name = $to_category_name;
	}

	public function getFromCategoryName() {
		return $this->from_category_name;
	}

	public function setFromCategoryName($from_category_name) {
		$this->from_category_name = $from_category_name;
	}

	public function getToAccountName() {
		return $this->to_account_name;
	}

	public function setToAccountName($to_account_name) {
		$this->to_account_name = $to_account_name;
	}

	public function getFromAccountName() {
		return $this->from_account_name;
	}

	public function setFromAccountName($from_account_name) {
		$this->from_account_name = $from_account_name;
	}
}

