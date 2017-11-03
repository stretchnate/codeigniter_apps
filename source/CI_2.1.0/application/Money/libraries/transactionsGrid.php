<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TransactionsGrid extends N8_Error {
	private $CI;
	private $owner_type;
	private $owner;
	private $owner_id;
	private $transactions;
	private $transactions_grid;
	private $start_date;
	private $end_date;
	private $reporting; //used when running reports.
	private $theads = array(
		"transaction_id" => "ID",
		"transaction_date" => "Date",
		"transaction_amount" => "Amount",
		"transaction_info" => "Description",
//		"cleared_bank" => "Cleared",
		"delete_transaction" => "Delete"
	);

	private $offset;

	function __construct($owner = null, $owner_type = null, $reporting = false) {
		$this->CI =& get_instance();
		// Load additional libraries, helpers, etc.
		$this->CI->load->library('session');
		$this->CI->load->database();
		$this->CI->load->helper('url');

		$this->owner_type = $owner_type;
		if($owner) {
			$this->loadOwner($owner);
		}

		$this->reporting = $reporting;
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
	 * calculates the balance spent for the report being run
	 *
	 * @return type
	 */
	private function calculateBalances() {
		$balances['deposit_total'] = 0;
		$balances['deduction_total'] = 0;
		$balances['balance'] = 0;
		if(isset($this->transactions) && is_array($this->transactions)) {
			foreach($this->transactions as $transaction) {
				if($transaction->to_category == $this->owner_id) {
					$balances['deposit_total'] = $balances['deposit_total'] + $transaction->transaction_amount;
					$balances['balance'] = $balances['balance'] + $transaction->transaction_amount;
				} elseif($transaction->from_category == $this->owner_id) {
					$balances['deduction_total'] = $balances['deduction_total'] + $transaction->transaction_amount;
					$balances['balance'] = $balances['balance'] - $transaction->transaction_amount;
				}
			}
		}

		return $balances;
	}
	/**
	 * determines what transactions to get and gets them from the db
	 * to_category can have a from_category or from_account
	 * from_category can have a to_category only
	 * from_account can have a to_account or to_category
	 * to_account can have a from_account or deposit_id
	 * deposit_id can have a to_account only
	 *
	 * @param int $limit
	 */
	private function getTransactions($limit = 20, $offset = 0) {
		$this->offset = $offset;

		$where = "transactions.owner_id = ".$this->CI->session->userdata("user_id");

		if($this->owner_type == "account") {
			$where .= " AND (to_account = {$this->owner_id} OR from_account = {$this->owner_id})";
		} else if($this->owner_type == "category") {
			$where .= " AND (to_category = {$this->owner_id} OR from_category = {$this->owner_id})";
		}

		if(isset($this->start_date)) {
			if(!isset($this->end_date)) {
				$this->end_date = new DateTime();
			}

			$where .= " AND (DATE_FORMAT(`transaction_date`, '%Y-%m-%d') BETWEEN '".$this->start_date->format("Y-m-d")."' AND '".$this->end_date->format("Y-m-d")."')";
		}

		$this->CI->db->select()
					->from("transactions")
					->join("cleared_transactions", "transactions.transaction_id = cleared_transactions.transactionId AND cleared_transactions.end_date IS NULL", "left")
					->where($where, null, false)
                    ->order_by("transaction_id", "desc")
                    ->limit($limit, $this->offset);
		$query = $this->CI->db->get();

		return $query->result();
	}

	/**
	 * @param string $theads
	 */
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
		$html = "<div id='transactions'>"
				.$this->generateBootstrapGrid()
				."</div>";

		if($this->reporting === true) {
			$balances = $this->calculateBalances();
			$error = ($balances['balance'] < 0) ? " class='error'" : '';
			$html .= "<div class='bold' style='text-align:right'>"
					. "<div>Total Deposited: ".money_format('%i',$balances['deposit_total'])."</div>"
					. "<div>Total Spent: ".money_format('%i',$balances['deduction_total'])."</div><hr>"
					. "<div{$error}>Balance: ".money_format('%i',$balances['balance'])."</div></div>";
		}

		return $html;
	}

	/**
	 * generate the pagination links for the transactions grid
	 * @return string
	 */
	private function generatePagination() {
		$html = array(
			'<nav aria-label="Transaction Pages">',
			'<ul class="pagination">',
			'<li class="page-item">
				<a class="page-link" href="javascript:void(0)" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
				<span class="sr-only">Previous</span>
			  </a>
			</li>'
		);

		$page_count = $this->getTransactionsTotal() / count($this->transactions);
		for($i = 1;$i <= $page_count;$i++) {
			$html[] = '<li class="page-item"><a class="page-link" href="javascript:void(0)">'.$i.'</a></li>';
		}

		$html[] = '<li class="page-item">
					<a class="page-link" href="javascript:void(0)">
						<span aria-hidden="true">&raquo;</span>
						<span class="sr-only">Next</span>
					</a>
				</li>';
		$html[] = '</ul>';
		$html[] = '</nav>';

		return implode('', $html);
	}

	/**
	 * returns the total number of transactions
	 * @return type
	 */
	private function getTransactionsTotal() {
		$where = "transactions.owner_id = ".$this->CI->session->userdata("user_id");

		if($this->owner_type == "account") {
			$where .= " AND (to_account = {$this->owner_id} OR from_account = {$this->owner_id})";
		} else if($this->owner_type == "category") {
			$where .= " AND (to_category = {$this->owner_id} OR from_category = {$this->owner_id})";
		}

		if(isset($this->start_date)) {
			if(!isset($this->end_date)) {
				$this->end_date = new DateTime();
			}

			$where .= " AND (DATE_FORMAT(`transaction_date`, '%Y-%m-%d') BETWEEN '".$this->start_date->format("Y-m-d")."' AND '".$this->end_date->format("Y-m-d")."')";
		}

		$this->CI->db->select('count(*) as total')
					->from("transactions")
					->where($where, null, false);
		$query = $this->CI->db->get();

		return $query->row()->total;
	}

	/**
	 * adjust grid for screensize
	 *
	 * @return string
	 */
	private function responsiveJS() {
		ob_start();
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				adjustGrid();

				$(window).resize(function() {
					adjustGrid();
				});
			});

			function adjustGrid() {
				if($('nav.navbar').width() < 992) {
					$('#transactions_grid div.optional').hide();
					$('#transactions_grid div.adjust').addClass('col-xs-3').removeClass('col-xs-1');
					$('#transactions_grid span.year').hide();
				} else {
					$('#transactions_grid span.year').show();
					$('#transactions_grid div.adjust').addClass('col-xs-1').removeClass('col-xs-3');
					$('#transactions_grid div.optional').show();
				}
			}
		</script>
		<?php
		$js = ob_get_contents();
		ob_end_flush();

		return $js;
	}

	/**
	 * generate a grid system without table elements (bootstrap)
	 */
	private function generateBootstrapGrid() {
		$html = $this->responsiveJS();
		$html .= "<div id='transactions_grid' class='well'>
						<div id='grid'>
						<div class='row header'>";
							foreach($this->theads as $property => $thead) {
								switch($property) {
									case "transaction_id":
									case "transaction_date":
									case "transaction_amount":
									case "delete_transaction":
									case "cleared_bank":
										$html .= "<div class='col-xs-1 adjust'>".$thead."</div>";
										break;

									case "transaction_info":
										$html .= "<div class='col-xs-8 optional'>".$thead."</div>";
										break;
								}
							}
						$html .= "</div>";

						foreach($this->transactions as $transaction) {
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

							$html .= "<div class='row transaction'>";
							foreach($this->theads as $property => $value) {
								switch($property) {
									case "transaction_date":
										$date = date("m/d", strtotime($transaction->transaction_date));
										$date .= "<span class='year'>".date("/Y", strtotime($transaction->transaction_date))."</span>";
										$html .= "<div class='col-xs-1 adjust date'>".$date."</div>";
										break;

									case "transaction_amount":
										$html .= "<div class='col-xs-1 adjust {$add_subtract}'>".number_format($transaction->transaction_amount, 2, ".", ",")."</div>";
										break;

									case "transaction_info":
										$html .= "<div class='col-xs-8 optional' style='text-align:right'>".$transaction->transaction_info."</div>";
										break;

									case "cleared_bank":
										$html .= "<div class='col-xs-1 adjust' style='text-align:center'><input type='checkbox' value='".$transaction->transaction_id."' {$checked}/></div>";
										break;

                                    case "delete_transaction":
                                        $html .= "<div class='col-xs-1 adjust' style='text-align:center'>"
                                                . "<form method='post' action='/funds/deleteTransaction/".$transaction->transaction_id."' class='delete_transaction_form'>"
                                                    . "<input type='hidden' name='return_uri' value='".$this->CI->uri->uri_string."' />"
                                                    . "<input type='image' src='/images/small_red_ex.png' alt='delete transaction {$transaction->transaction_id}' title='delete transaction {$transaction->transaction_id}' />"
                                                . "</form>"
                                            . "</div>";
                                        break;

									default:
										if( property_exists("Budget_DataModel_TransactionDM", $property) && isset($transaction->$property) ) {
											$html .= "<div class='col-xs-1 adjust'>".$transaction->$property."</div>";
										}
								}
							}
							$html .= "</div>";
						}
		$html .= "</div>";
		$html .= $this->generatePagination();
		$html .= "</div>";

		return $html;
	}

	public function getTransactionsGrid() {
		return $this->transactions_grid;
	}

	/**
	 * sets the start date object
	 * @param DateTime $start_date
	 * @return \TransactionsGrid
	 */
	public function setStartDate(DateTime $start_date) {
		$this->start_date = $start_date;
		return $this;
	}

	/**
	 * sets the end date object
	 * @param DateTime $end_date
	 * @return \TransactionsGrid
	 */
	public function setEndDate(DateTime $end_date) {
		$this->end_date = $end_date;
		return $this;
	}
}
// End of library class
// Location: application/libraries/transactionsGrid.php