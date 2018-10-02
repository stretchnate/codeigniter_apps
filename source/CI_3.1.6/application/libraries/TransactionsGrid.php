<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TransactionsGrid extends N8_Error {
	private $CI;

	private $transactions_grid;
	private $theads = array(
		"transaction_id" => "ID",
		"transaction_amount" => "Amount",
		"transaction_info" => "Description",
		"transaction_date" => "Date",
		"delete_transaction" => "Delete"
	);

	private $transaction_parent;

	private $transaction_type;

	/**
	 *
	 */
	function __construct($transaction_parent = null, $transaction_type = 'category') {
		$this->transaction_parent = !is_null($transaction_parent) ? "'$transaction_parent'" : 'null';
		$this->transaction_type = !is_null($transaction_type) ? $transaction_type : 'category';

		$this->CI =& get_instance();
		// Load additional libraries, helpers, etc.
		$this->CI->load->library('session');
		$this->CI->load->database();
		$this->CI->load->helper('url');
	}

	/**
	 * controls creating the transactionsGrid
	 *
	 * @param owner Int/Object
	 * @param owner_type = String
	 */
	public function run() {
		$this->transactions_grid = $this->generateGrid();
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
					."</div>
				</div>";

		return $html;
	}

	/**
	 * adjust grid for screensize
	 *
	 * @return string
	 */
	private function customJS() {
		$js = "
		<script type='text/javascript'>
			$(document).ready(function() {
				adjustGrid();

				$(window).resize(function() {
					adjustGrid();
				});

				var data = {
					transaction_parent: $this->transaction_parent,
					transaction_type: \"$this->transaction_type\"
				};

				if($('select[name=accounts_select]').attr('name')) {
					data.transaction_parent = $('select[name=accounts_select]').val();
				}
				if($('input[name=transfer-funds]').attr('name')) {
					$('input[name=transfer-funds]').each(function() {
						if($(this).attr('checked')) {
							data.transaction_type = $(this).attr('id').replace(/from-(accounts|categories)-radio/, \"$1\");
						}
					});
				}

				var table = $('#transactions_table').DataTable({
					pagingType: 'full_numbers',
					processing: true,
					serverSide: true,
					ajax: {url: '".base_url('transaction/getPage')."', type: \"POST\", data: data},
					columns: [
						{data: 'transaction_id'},
						{data: 'transaction_amount', type: 'num'},
						{data: 'transaction_info'},
						{data: 'transaction_date', type: 'date'},
						{data: 'delete_transaction', type: 'html'}
					]
				});
				
				$(document).on('click', 'img.delete_transaction', function() {
				    var id = $(this).attr('title').split(' ')[2];
                    $.get('/funds/deleteTransaction/'+id, function(result) {
                        if(result) {
                            location.reload();
                        } else {
                            alert('There was a problem deleting the transaction.');
                        }
                    });
                    
                    return false;
				});
			});

			function adjustGrid() {
				if($('nav.navbar').width() < 992) {
					$('#transactions_grid th.optional').hide();
					$('#transactions_grid span.optional').parent().hide();
					$('#transactions_grid span.year').hide();
				} else {
					$('#transactions_grid span.year').show();
					$('#transactions_grid th.optional').show();
					$('#transactions_grid span.optional').parent().show();
				}
			}
		</script>";

		return $js;
	}

	/**
	 * generate a grid system without table elements (bootstrap)
	 * @return string
	 */
	private function generateBootstrapGrid() {
		$html = "<table id='transactions_table'><thead><tr>";
					foreach($this->theads as $property => $thead) {
						switch($property) {
							case "transaction_id":
							case "transaction_date":
							case "transaction_amount":
							case "delete_transaction":
							case "cleared_bank":
								$html .= "<th class='col-xs-1'>$thead</th>";
								break;

							case "transaction_info":
								$html .= "<th class='col-xs-8 optional'>$thead</th>";
								break;
						}
					}
		$html .= "</tr></thead>";

		$html .= "</table>";
		return $html;
	}

	/**
	 * @return type
	 */
	public function getTransactionsGrid() {
		return $this->transactions_grid;
	}
}
// End of library class
// Location: application/libraries/transactionsGrid.php