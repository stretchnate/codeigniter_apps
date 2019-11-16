<?php
	class Budget_BusinessModel_Home extends N8_Model {

		/**
		 * @var Budget_DataModel_AccountDM[]
		 */
		protected $accounts = array();

		function __construct(){
			parent::__construct();
		}

		/**
		 * @param int $owner_id
		 */
		public function loadAccounts($owner_id) {
			$account_ids = $this->fetchAccountIds($owner_id);

			foreach($account_ids as $account) {
				$account_dm = new Budget_DataModel_AccountDM($account->account_id, $owner_id);
				$account_dm->loadCategories();

				$this->accounts[] = $account_dm;
			}
		}

		/**
		 * @param int $owner_id
		 * @return stdClass[]
		 */
		private function fetchAccountIds($owner_id) {
			$this->db->select('account_id')
					->from('accounts')
					->where(array('owner_id' => $owner_id, 'active' => 1));

			$query = $this->db->get();

			return $query->result();
		}

		/**
		 * @return Budget_DataModel_AccountDM[]
		 */
		public function getAccounts() {
			return $this->accounts;
		}
	}
?>
