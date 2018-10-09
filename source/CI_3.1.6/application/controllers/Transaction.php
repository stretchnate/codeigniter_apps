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
                $tdm = new Budget_DataModel_TransactionDM($id);
//need to find owning account
                if($tdm->getFromAccount()) {
                    $account_id = $tdm->getFromAccount();
                } elseif($tdm->getFromCategory()) {
                    $category = new Budget_DataModel_CategoryDM($tdm->getFromCategory(), $this->session->user_id);
                    $account_id = $category->getParentAccountId();
                }

                $categories = new \Category\Rows();
                $categories->load(['active' => true, 'account_id' => $account_id]);
            } catch(Exception $e) {
                log_message('error', $e->getMessage());
            }
        }
	}
