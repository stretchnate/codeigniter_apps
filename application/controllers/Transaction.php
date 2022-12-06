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

        /**
         * @param $id
         */
		public function edit() {
		    $id = $this->input->post('transaction_id');
		    try {
                $tdm = new \Transaction\Row($id);
                $structure = new \Transaction\Fields();
                $structure->setTransactionAmount($this->input->post('amount'));
                $structure->setTransactionDate($this->input->post('date'));
                $structure->setTransactionInfo($this->input->post('description'));
                switch($tdm->getTransactionType()) {
                    case "deduction":
                        $structure->setFromCategory($tdm->getStructure()->getFromCategory());
                        $manager = new \Transaction\Deduction\Manager();
                        break;
                    case "category_to_category_transfer":
                        $structure->setToCategory($tdm->getStructure()->getToCategory());
                        $structure->setFromCategory($tdm->getStructure()->getFromCategory());
                        $manager = new \Transaction\Category\Transfer\Manager();
                        break;
                    case "refund":
                        $structure->setToCategory($tdm->getStructure()->getToCategory());
                        $manager = new \Transaction\Refund\Manager();
                        break;
                    case "account_to_category_deposit":
                        $structure->setToCategory($tdm->getStructure()->getToCategory());
                        $manager = new \Transaction\Category\Deposit\Manager();
                        break;
                    case "account_to_account_transfer":
                    case "deposit":
                    default:
                        $message = 'transaction type ['.$tdm->getTransactionType().'] cannot be modified.';
                        log_message('error', $message);
                        exit(json_encode(['success' => false, 'message' => $message]));
                }

                $manager->modify($tdm, $structure, $this->session->user_id);
                exit(json_encode(['success' => true]));
            } catch(Exception $e) {
                log_message('error', $e->getMessage());
                exit(json_encode(['success' => false]));
            }
        }

	}
