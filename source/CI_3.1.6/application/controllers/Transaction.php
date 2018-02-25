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
			$grid = new TransactionsGrid($this->input->post('transaction_parent'), $this->input->post('transaction_type'));
			echo $grid->getPage($this->input->post('limit'), $this->input->post('offset'));
		}
	}
