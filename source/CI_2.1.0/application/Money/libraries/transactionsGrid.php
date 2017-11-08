<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TransactionsGrid extends N8_Error {
	private $CI;


	private $transaction_type;
	private $transaction_parent;
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

	function __construct($transaction_parent = null, $transaction_type = null, $reporting = false) {
		$this->CI =& get_instance();
		// Load additional libraries, helpers, etc.
		$this->CI->load->library('session');
		$this->CI->load->database();
		$this->CI->load->helper('url');

		$this->transaction_type = $transaction_type;
		if($transaction_parent) {
			$this->loadTransactionParent($transaction_parent);
		}

		$this->reporting = $reporting;
	}

	/**
	 * controls creating the transactionsGrid
	 *
	 * @param owner Int/Object
	 * @param owner_type = String
	 */
	public function run($transaction_parent = null, $owner_type = null) {
		if(!$this->transaction_type && $owner_type) {
			$this->transaction_type = $owner_type;
		}

		if(!$this->transaction_parent && $transaction_parent) {
			$this->loadTransactionParent($transaction_parent);
		}

		//run remaining methods in proper order until transaction grid is created.
		$this->transactions = $this->getTransactions();

		if( is_array($this->transactions) && count($this->transactions) > 0) {
			$this->transactions_grid = $this->generateGrid();
		} else {
			$this->setError("No transactions found for ".$owner_type." ".$transaction_parent, "info");
		}
	}

	/**
	 * get a page of transactions
	 * @param int $limit
	 * @param int $offset
	 * @return string
	 */
	public function getPage($limit, $offset) {
		$this->transactions = $this->getTransactions($limit, $offset);

		return $this->generateBootstrapGrid();
	}

	/**
	 * load the parent data model
	 * @param type $transaction_parent
	 */
	private function loadTransactionParent($transaction_parent) {
		if( is_object($transaction_parent) ) {
			$this->transaction_parent = $transaction_parent;
		} else {
			switch($this->transaction_type) {
				case "account":
					$this->transaction_parent    = new Budget_DataModel_AccountDM($transaction_parent);
					break;
				case "category":
				default:
					$this->transaction_parent    = new Budget_DataModel_CategoryDM($transaction_parent);
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
				if($transaction->to_category == $this->transaction_parent->getID()) {
					$balances['deposit_total'] = $balances['deposit_total'] + $transaction->transaction_amount;
					$balances['balance'] = $balances['balance'] + $transaction->transaction_amount;
				} elseif($transaction->from_category == $this->transaction_parent->getID()) {
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

		if($this->transaction_type == "account") {
			$where .= " AND (to_account = {$this->transaction_parent->getID()} OR from_account = {$this->transaction_parent->getID()})";
		} else if($this->transaction_type == "category") {
			$where .= " AND (to_category = {$this->transaction_parent->getID()} OR from_category = {$this->transaction_parent->getID()})";
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
		$html = "<div id='transactions'>";
		$html .= $this->customJS();
		$html .= "	<div id='transactions_grid' class='well'>
						<div id='grid'>"
						. $this->generateBootstrapGrid()
						."</div>"
						. $this->generatePagination()
					."</div>
				</div>";

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
		$html = array();
		$page_count = ceil($this->getTransactionsTotal() / count($this->transactions));
		if($page_count > 2) {
			$html[] = '<nav aria-label="Transaction Pages">';
			$html[] = '<ul class="pagination">';
			$html[] = '<li class="page-item">
					<a class="page-link left-end disabled" href="javascript:void(0)" aria-label="First">
					<span aria-hidden="true">&laquo;</span>
					<span class="sr-only">First</span>
				  </a>
				</li>';
			$html[] = '<li class="page-item">
					<a class="page-link left-end disabled" href="javascript:void(0)" aria-label="Previous">
					<span aria-hidden="true">&lsaquo;</span>
					<span class="sr-only">Previous</span>
				  </a>
				</li>';

			for($i = 1;$i <= $page_count;$i++) {
				$active = null;
				if($i == 1) {
					$active = " active";
				}
				$html[] = '<li class="page-item"><a class="page-link'.$active.'" href="javascript:void(0)" aria-label="'.$i.'">'.$i.'</a></li>';
			}

			$disabled = $page_count < 2 ? ' disabled' : null;
			$html[] = '<li class="page-item">
						<a class="page-link right-end'.$disabled.'" href="javascript:void(0)" aria-label="Next">
							<span aria-hidden="true">&rsaquo;</span>
							<span class="sr-only">Next</span>
						</a>
					</li>';
			$html[] = '<li class="page-item">
						<a class="page-link right-end'.$disabled.'" href="javascript:void(0)" aria-label="Last">
							<span aria-hidden="true">&raquo;</span>
							<span class="sr-only">Last</span>
						</a>
					</li>';
			$html[] = '</ul>';
			$html[] = '</nav>';
			$html[] = '<input type="hidden" id="transaction_limit" value="20" />';
		}

		return implode('', $html);
	}

	/**
	 * returns the total number of transactions
	 * @return type
	 */
	private function getTransactionsTotal() {
		$where = "transactions.owner_id = ".$this->CI->session->userdata("user_id");

		if($this->transaction_type == "account") {
			$where .= " AND (to_account = {$this->transaction_parent->getID()} OR from_account = {$this->transaction_parent->getID()})";
		} else if($this->transaction_type == "category") {
			$where .= " AND (to_category = {$this->transaction_parent->getID()} OR from_category = {$this->transaction_parent->getID()})";
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
	private function customJS() {
		$js = '
		<script type="text/javascript">
			$(document).ready(function() {
				adjustGrid();

				$(window).resize(function() {
					adjustGrid();
				});

				$("#transactions_grid a.page-link").click(function() {
					if($(this).attr("class").match(/disabled/)) {
						return false;
					}
					var transaction_parent = null;
					var transaction_type = "category";
					if($("select[name=accounts_select]").attr("name")) {
						transaction_parent = $("select[name=accounts_select]").val();
					}
					if($("input[name=transfer-funds]").attr("name")) {
						transaction_type = $("input[name=transfer-funds]").val().replace(/from-(accounts|categories)-radio/, $1);
					}

					var offset = null;
					var factor = 0;
					var total_links = $("a.page-link").length;
					switch($(this).attr("aria-label")) {
						case "Next":
							factor = parseInt($("a.page-link.active").text());
							offset = factor * parseInt($("#transaction_limit").val());
							break;
						case "Last":
							factor = total_links - 5;
							offset = factor * parseInt($("#transaction_limit").val());
							break;
						case "Previous":
							factor = (parseInt($("a.page-link.active").text()) - 2);
							offset = factor * parseInt($("#transaction_limit").val());
							break;
						case "First":
							factor = (parseInt($("a.page-link:eq(2)").text()) - 1);
							offset = factor * parseInt($("#transaction_limit").val());
							break;
						default:
							factor = (parseInt($(this).text()) - 1);
							offset = factor * parseInt($("#transaction_limit").val());
					}

					$("a.page-link").removeClass("active").removeClass("disabled");

					var target = factor + 2;
					if(target < 3) {
						$(".left-end").addClass("disabled");
					} else if(target > (total_links - 4)) {
						$(".right-end").addClass("disabled");
					}

					$("a.page-link:eq("+target+")").addClass("active");

					var data = {
						limit: $("#transaction_limit").val(),
						transaction_parent: transaction_parent,
						transaction_type: transaction_type,
						offset: offset
					};

					$.post("/transaction/getPage", data, function(response) {
						$("#grid").html(response);
					}, "html");
				});
			});

			function adjustGrid() {
				if($("nav.navbar").width() < 992) {
					$("#transactions_grid div.optional").hide();
					$("#transactions_grid div.adjust").addClass("col-xs-3").removeClass("col-xs-1");
					$("#transactions_grid span.year").hide();
				} else {
					$("#transactions_grid span.year").show();
					$("#transactions_grid div.adjust").addClass("col-xs-1").removeClass("col-xs-3");
					$("#transactions_grid div.optional").show();
				}
			}
		</script>';

		return $js;
	}

	/**
	 * generate a grid system without table elements (bootstrap)
	 * @return string
	 */
	private function generateBootstrapGrid() {
		$html = "<div class='row header'>";
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

					if($this->transaction_parent) {
						if($this->transaction_parent->getID() == $transaction->to_category || $this->transaction_parent->getID() == $transaction->to_account) {
							$add_subtract = "add";
						} else if($this->transaction_parent->getID() == $transaction->from_category || $this->transaction_parent->getID() == $transaction->from_account) {
							$add_subtract = "subtract";
						}
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