<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TransactionsGrid extends N8_Error {
	private $CI;
	private $owner_type;
	private $owner;
	private $owner_id;
	private $transactions;
	private $transactions_grid;
	private $theads = array("transaction_id" => "ID",
							"transaction_date" => "Date",
							"transaction_amount" => "Amount",
							"transaction_info" => "Description",
							"cleared_bank" => "Cleared");

	function __construct($owner = null, $owner_type = null) {
		$this->CI =& get_instance();
		// Load additional libraries, helpers, etc.
		$this->CI->load->library('session');
		$this->CI->load->database();
		$this->CI->load->helper('url');

		$this->owner_type = $owner_type;
		if($owner) {
			$this->loadOwner($owner);
		}
	}

	/**
	 * controls creating the transactionsGrid
	 *
	 * @param owner Int/Object
	 * @param owner_type = String
	 */
	public function run($owner = null, $owner_type = null) {
		if(!$this->owner_type && $owner_type) {
			$this->owner_type = $owner_type;
		}

		if(!$this->owner && $owner) {
			$this->loadOwner($owner);
		}

		//run remaining methods in proper order until transaction grid is created.
		$this->transactions = $this->getTransactions();

		if( is_array($this->transactions) && count($this->transactions) > 0) {
			$this->transactions_grid = $this->generateGrid();
		} else {
			$this->setError("No transactions found for ".$owner_type." ".$owner, "info");
		}
	}

	private function loadOwner($owner) {
		if( is_object($owner) ) {
			$this->owner = $owner;
		} else {
			switch($this->owner_type) {
				case "account":
					$this->owner    = new Budget_DataModel_AccountDM($owner);
					$this->owner_id = $this->owner->getAccountId();
					break;

				case "category":
					$this->owner    = new Budget_DataModel_CategoryDM($owner);
					$this->owner_id = $this->owner->getCategoryId();
					break;
			}
		}
	}

	/**
	 * determines what transactions to get and gets them from the db
	 * to_category can have a from_category or from_account
	 * from_category can have a to_category only
	 * from_account can have a to_account or to_category
	 * to_account can have a from_account or deposit_id
	 * deposit_id can have a to_account only
	 *
	 * @param $transactions
	 */
	private function getTransactions() {
		$where = "transactions.owner_id = ".$this->CI->session->userdata("user_id");

		if($this->owner_type == "account") {
			$where .= " AND to_account = {$this->owner_id} OR from_account = {$this->owner_id}";
		} else if($this->owner_type == "category") {
			$where .= " AND to_category = {$this->owner_id} OR from_category = {$this->owner_id}";
		}

		$this->CI->db->select()
					->from("transactions")
					->join("cleared_transactions", "transactions.transaction_id = cleared_transactions.transactionId AND cleared_transactions.end_date IS NULL", "left")
					->where($where, null, false);
		$query = $this->CI->db->get();

		return $query->result();
	}

	public function setTheads($theads) {
		if(!array_keys($theads, "cleared_bank")) {
			$theads["cleared_bank"] = "Cleared";
		}
		$this->theads = $theads;
	}
	/**
	 * generates the transactions grid and returns it as an HTML string
	 *
	 * @return string
	 */
	private function generateGrid() {
		$html = "<div id='transactions'>
					<script type='text/javascript'>
						$(document).ready(function() {
							$('#transactions_grid').dataTable({
								'aaSorting': [[0,'desc']]
							 });
						});
					</script>
					<table id='transactions_grid'>
						<thead>
							<tr>";
							foreach($this->theads as $thead) {
								$html .= "<th>".$thead."</th>";
							}
						$html .= "</tr>
						</thead>
						<tbody>";
						foreach($this->transactions as $transaction) {
							$cleared = null;
							$add_subtract = null;

							$checked = "";
							if($transaction->cleared_bank == 1) {
								$checked = "checked='checked' ";
							}

							if($this->owner_id == $transaction->to_category || $this->owner_id == $transaction->to_account) {
								$add_subtract = "add";
							} else if($this->owner_id == $transaction->from_category || $this->owner_id == $transaction->from_account) {
								$add_subtract = "subtract";
							}

							$html .= "<tr class='transaction'>";
							foreach($this->theads as $property => $value) {
								switch($property) {
									case "transaction_date":
										$html .= "<td>".date("m/d/Y g:i:s a", strtotime($transaction->transaction_date))."</td>";
										break;

									case "transaction_amount":
										$html .= "<td class='{$add_subtract}'>".number_format($transaction->transaction_amount, 2, ".", ",")."</td>";
										break;

									case "transaction_info":
										$html .= "<td align='right'>".$transaction->transaction_info."</td>";
										break;

									case "cleared_bank":
										$html .= "<td><input type='checkbox' value='".$transaction->transaction_id."' {$checked}/>";
										break;

									default:
										if( property_exists("Budget_DataModel_TransactionDM", $property) && isset($transaction->$property) ) {
											$html .= "<td>".$transaction->$property."</td>";
										}
								}
							}
							$html .= "</tr>";
						}
		$html .=		"</tbody>
					</table>
				</div>";

		return $html;
	}

	public function getTransactionsGrid() {
		return $this->transactions_grid;
	}
}
// End of library class
// Location: application/libraries/transactionsGrid.php