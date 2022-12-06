<?php
namespace Transaction;
/**
 * Class Row
 */
class Row extends \CI_Model {

	private $insert_id;

	//these are not stored in the db on transactions.
	private $to_category_name;
	private $from_category_name;
	private $to_account_name;
	private $from_account_name;

    /**
     * @var \Transaction\Fields
     */
	private $structure;

	function __construct($transaction_id = null) {
		parent::__construct();
		$this->structure = new Fields();

		if($transaction_id) {
			$this->loadTransaction($transaction_id);
		}
	}

	/**
	 * fetches the transaction from the db and loads the data into the class properties.
	 *
	 * @param transaction_id Int
	 * @return void
     * @throws \Exception
	 */
	public function loadTransaction(&$transaction_id) {
		$query = $this->db->get_where("transactions", ["transaction_id" => $transaction_id]);

		if(!$query) {
		    throw new \Exception($this->db->error()['message']);
        }

        if($query->num_rows() == 1) {
            $this->getStructure()->setTransactionId($query->row()->transaction_id)
                ->setToCategory($query->row()->to_category)
                ->setFromCategory($query->row()->from_category)
                ->setTransactionInfo($query->row()->transaction_info)
                ->setTransactionAmount($query->row()->transaction_amount)
                ->setTransactionDate($query->row()->transaction_date)
                ->setOwnerId($query->row()->owner_id)
                ->setDepositId($query->row()->deposit_id)
                ->setFromAccount($query->row()->from_account)
                ->setToAccount($query->row()->to_account);
        }
	}

	/**
	 * saves the properties into the transaction table
	 *
	 * @return bool
	 */
	public function saveTransaction() {

		if($this->validateTransaction()) {

			if($this->getStructure()->getTransactionId() > 0) {
				$result = $this->updateTransaction();
			} else {
                $result = $this->insertTransaction();
				$this->insert_id = $this->db->insert_id();
			}
			$this->auth->updateLoginHistory(TRUE);

			return $result;
		}
	}

	/**
	 * updates and existing transaction
	 *
	 * @return bool
	 */
	private function updateTransaction() {
		$sets = array();
		if($this->getStructure()->getToCategory()) {
			$sets["to_category"]    = $this->getStructure()->getToCategory();
		}

		if($this->getStructure()->getFromCategory()) {
			$sets["from_category"]  = $this->getStructure()->getFromCategory();
		}

		if($this->getStructure()->getToAccount()) {
			$sets["to_account"]     = $this->getStructure()->getToAccount();
		}

		if($this->getStructure()->getFromAccount()) {
			$sets["from_account"]   = $this->getStructure()->getFromAccount();
		}

		if($this->getStructure()->getDepositId()) {
			$sets["deposit_id"]     = $this->getStructure()->getDepositId();
		}

		$sets["transaction_amount"] = dbNumberFormat($this->getStructure()->getTransactionAmount());
		$sets["transaction_date"]   = $this->getStructure()->getTransactionDate();
		$sets["transaction_info"]   = $this->getStructure()->getTransactionInfo();

		if($this->db->where("transaction_id", $this->getStructure()->getTransactionId())->update("transactions", $sets)) {
			return true;
		}
		return false;
	}

	/**
	 * inserts a new transaction
	 *
	 * @return bool
	 */
	private function insertTransaction() {
		$values = array();

		if($this->getStructure()->getToCategory()) {
			$values["to_category"]    = $this->getStructure()->getToCategory();
		}

		if($this->getStructure()->getFromCategory()) {
			$values["from_category"]  = $this->getStructure()->getFromCategory();
		}

		if($this->getStructure()->getToAccount()) {
			$values["to_account"]     = $this->getStructure()->getToAccount();
		}

		if($this->getStructure()->getFromAccount()) {
			$values["from_account"]   = $this->getStructure()->getFromAccount();
		}

		if($this->getStructure()->getDepositId()) {
			$values["deposit_id"]     = $this->getStructure()->getDepositId();
		}

		$values["owner_id"]           = $this->getStructure()->getOwnerId();
		$values["transaction_amount"] = dbNumberFormat($this->getStructure()->getTransactionAmount());
		$values["transaction_date"]   = $this->getStructure()->getTransactionDate();
		$values["transaction_info"]   = $this->getStructure()->getTransactionInfo();

		return $this->db->insert("transactions", $values);
	}

    /**
     * deletes a transaction
     *
     * @return type
     */
    public function deleteTransaction() {
        if(!empty($this->getStructure()->getTransactionId())) {
            return $this->db->delete('transactions', array('transaction_id' => $this->getStructure()->getTransactionId()));
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
		if( !$this->getStructure()->getOwnerId() ) {
			$this->setError("No Owner ID for transaction");
		}

		$return = $this->getTransactionType();

		if($return === false) {
			$this->setError("Invalid Combination for transaction\nfrom account = ".$this->getStructure()->getFromAccount() . "to account = ".$this->getStructure()->getToAccount() .
							"from category = ".$this->getStructure()->getFromCategory() .
							"to category = ".$this->getStructure()->getToCategory() .
							"deposit id = ".$this->getStructure()->getDepositId());
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
		if($this->getStructure()->getFromAccount() && $this->getStructure()->getToCategory()) {
			if(!$this->getStructure()->getToAccount() && !$this->getStructure()->getFromCategory()) {
				return true;
			}
		}
		return false;
	}

	//category to category transfers
	protected function isCategoryToCategoryTransfer() {
		if($this->getStructure()->getFromCategory() && $this->getStructure()->getToCategory()) {
			if( !$this->getStructure()->getToAccount() && !$this->getStructure()->getFromAccount() && !$this->getStructure()->getDepositId() ) {
				return true;
			}
		}
		return false;
	}

	//account to account transfers
	protected function isAccountToAccountTransfer() {
		if($this->getStructure()->getFromAccount() && $this->getStructure()->getToAccount()) {
			if( !$this->getStructure()->getToCategory() && !$this->getStructure()->getFromCategory() && !$this->getStructure()->getDepositId() ) {
				return true;
			}
		}
		return false;
	}

	//deposits
	protected function isDeposit() {
		if($this->getStructure()->getDepositId() && $this->getStructure()->getToAccount()) {
			if( !$this->getStructure()->getToCategory() && !$this->getStructure()->getFromCategory() && !$this->getStructure()->getFromAccount() ) {
				return true;
			}
		}
		return false;
	}

	//deductions
	protected function isDeduction() {
		if($this->getStructure()->getFromCategory()) {
			if( !$this->getStructure()->getToCategory() && !$this->getStructure()->getToAccount() && !$this->getStructure()->getDepositId() && !$this->getStructure()->getFromAccount() ) {
				return true;
			}
		}
		return false;
	}

	//refunds
	protected function isRefund() {
		if($this->getStructure()->getToCategory()) {
			if( !$this->getStructure()->getFromCategory() && !$this->getStructure()->getToAccount() && !$this->getStructure()->getDepositId() && !$this->getStructure()->getFromAccount() ) {
				return true;
			}
		}
		return false;
	}

	/** *********************************************
	 * Getters and Setters
	 ************************************************/
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

    /**
     * @return \Transaction\Fields
     */
    public function getStructure() {
	    return $this->structure;
    }
}

