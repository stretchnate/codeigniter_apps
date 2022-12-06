<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TransactionsGrid extends N8_Error {
	private $CI;

	private $transactions_grid;
	private $theads = array(
		"transaction_id" => "ID",
		"transaction_amount" => "Amount",
		"transaction_info" => "Description",
		"transaction_date" => "Date",
		"transaction_actions" => "Actions"
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
						{data: 'transaction_amount', type: 'num', className: 'editable'},
						{data: 'transaction_info', className: 'editable'},
						{data: 'transaction_date', type: 'date', className: 'editable'},
						{data: 'transaction_actions', type: 'html'}
					]
				});
				
				$(document).on('click', 'span.delete_transaction', function() {
				    var id = $(this).parents('tr').children('td:first-child').text();
				    $('body').overlay('message', 'Deleting transaction id ' + id);
                    $.get('/funds/deleteTransaction/'+id, function(result) {
                        $('body').overlay('remove');
                        if(result) {
//                            table.ajax.reload();//this is the right way to reload the table but I need to update the amounts above the form when present.
                            location.reload();
                        } else {
                            alert('There was a problem deleting the transaction.');
                        }
                    });
                    
                    return false;
				});
				
				$('table#transactions_table').on('click', 'span.edit_row', function() {
				    var amount = null;
				    var description = null;
				    var date = null;
				    var checked = null;
				    var transaction_id = $(this).parents('tr').children('td:first-child').text();
				    var month;
                    var day;
                    var year;
				    $(this).parents('tr').children('td.editable').each(function() {
				        if($(this).children('span.add').length || $(this).children('span.subtract').length) {
                            checked = $(this).children('span')[0].className;
				            amount = $(this).children('span.'+checked)[0].innerText;
				        } else if($(this).children('span.year').length) {
				            date = new Date($(this).text());
				            month = date.getMonth() + 1;
				            day = date.getDate();
				            year = date.getFullYear();
				            if(month < 10) {
				                month = month.toString().padStart(2, 0);
				            }
				            if(day < 10) {
				                day = day.toString().padStart(2, 0);
				            }
				        } else {
				            description = $(this).text();
				        }
				    });

				    var modal = $('#transaction_modal').modal();
				    modal.find('span#transaction_id').text(transaction_id);
				    modal.find('input[name=transaction_id]').val(transaction_id);
                    modal.find('textarea[name=description]').val(description);
                    modal.find('input[name=amount]').val(amount);
                    modal.find('input[name=date]').val(year+'-'+month+'-'+day);
				});
				
				$('.modal-footer').on('click', '#save_changes', function() {
				    var amount = $('#transaction_modal form input[name=amount]');
				    var description = $('#transaction_modal form textarea[name=description]');
				    var date = $('#transaction_modal form input[name=date]');
				    var send = true;
				    if(!amount.val() || isNaN(parseFloat(amount.val()))) {
				        amount.addClass('danger');
				        send = false;
				    }
				    if(!description.val()) {
				        description.addClass('danger');
				        send = false;
				    }
				    if(!date.val()) {
				        date.addClass('danger');
				        send = false;
				    }
				    if(send) {
				        $.post('/transaction/edit/', $('#transaction_modal form').serialize(), function(response) {
				            if(response.success) {
				                $('#transaction_update_alert span.text').text('Transaction updated.');
				                $('#transaction_update_alert').removeClass('alert-danger').addClass('alert-info').show();
				                amount.val('');
                                description.val('');
                                date.val('');
                                $('#transaction_modal input[name=transaction_id]').val('');
                                $('#transaction_modal').modal('hide');
				                setTimeout(function() {
//                                  table.ajax.reload();//this is the right way to reload the table but I need to update the amounts above the form when present.
                                    location.reload();
                                }, 3000);
				            } else {
				                var message = (response.message) ? response.message : 'Unable to update transaction.';
                                $('#transaction_update_alert span.text').text(message);
                                $('#transaction_update_alert').addClass('alert-danger').removeClass('alert-info').show();
				            }
				            
				            setTimeout(function() {
				                $('#transaction_update_alert').fadeOut(1000);
				            }, 3000);
				        }, 'json');
				    }
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
	    $html = '<div class="alert alert-danger alert-dismissible fade in" id="transaction_update_alert" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="text">Unable to update transaction.</span>
                </div>';
		$html .= "<table id='transactions_table'><thead><tr>";
					foreach($this->theads as $property => $thead) {
						switch($property) {
							case "transaction_id":
							case "transaction_date":
							case "transaction_amount":
							case "transaction_actions":
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
		$html .= $this->modal();
		return $html;
	}

	private function modal() {
	    return '<div class="modal fade" id="transaction_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Edit Transaction <span id="transaction_id"></span></h4>
                            </div>
                            <div class="modal-body">
                                <form id="transaction_edit_form" method="post" action="/transaction/edit">
                                    <input type="hidden" value="" name="transaction_id">
                                    <div>
                                        <input type="date" value="" name="date" class="form-control" placeholder="Date">
                                    </div>
                                    <div>
                                        <textarea name="description" class="form-control" placeholder="Description"></textarea>
                                    </div>
                                    <div>
                                        <input type="text" value="" name="amount" class="form-control" placeholder="Amount">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button id="save_changes" type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>';
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