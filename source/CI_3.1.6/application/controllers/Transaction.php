<?php
	/**
	 * Transaction controller
	 *
	 * @author stretch
	 */
	class Transaction extends N8_Controller {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * ajax method to get a page of transactions
		 */
		public function getPage() {
			$this->auth->restrict();

			try {
				$response = new stdClass();
				$response->draw = $this->input->post('draw');

				$parent = $this->input->post('transaction_parent');
				$helper = new \Transaction\Grid\Helper($this->session->user_id);
				$response->data = $helper->getPage( $this->input->post('length'), $this->input->post('start'), $this->input->post('transaction_type'), $parent);
				$response->recordsTotal = (int)$helper->getTotalRecords($this->input->post('transaction_type'), $parent)->total_records;
				$response->recordsFiltered = $response->recordsTotal;
			} catch(Exception $e) {
				$response->error = 'There was a problem fetching the page';
				log_message('error', $e->getMessage());
			}

			echo json_encode($response);
		}

		public function edit($id) {
		    try {
                $tdm = new \Transaction\Row($id);
                switch($tdm->getTransactionType()) {
                    case "deduction":
                        if($this->input->post('operator') == 'add') {
                            //no longer a deduction, need to get account dm and see if the amount can be changed
                        } else {

                        }
                        break;
                    case "category_to_category_transfer":
                        //need to make sure from category can handle the new amount
                        break;
                    case "refund":
                        if($this->input->post('operator') == 'subtract') {
                            //no longer a refund, now it's a deduction
                        } else {
                            //update refund
                        }
                        break;
                    case "account_to_category_deposit":
                        if($this->input->post('operator') == 'subtract') {
                            //no longer a deposit, need to put the amount back in the account as well as deduct it from the category
                        } else {
                            //need to make sure the account can handle the new amount
                        }
                        break;
                    case "account_to_account_transfer":
                    case "deposit":
                    default:
                }
                $tdm->setTransactionInfo($this->input->post('description'));
                $tdm->setTransactionDate($this->input->post('date'));
                $tdm->setTransactionAmount($this->input->post('amount'));

            } catch(Exception $e) {
                log_message('error', $e->getMessage());
            }
        }

	}
