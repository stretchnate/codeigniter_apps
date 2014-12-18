<?php
	class Budget_BusinessModel_Home extends N8_Model {

		protected $accounts = array();

		function __construct(){
			parent::__construct();
		}

		public function loadAccounts($owner_id) {
			// $query = $this->db->get_where('accounts',array('owner_id' => $id, 'active' => 1));
			$account_ids = $this->fetchAccountIds($owner_id);

			foreach($account_ids as $account) {
				$account_dm = new Budget_DataModel_AccountDM($account->account_id);
				$account_dm->loadCategories();

				$this->accounts[] = $account_dm;
			}
		}

		private function fetchAccountIds($owner_id) {
			$this->db->select('account_id')
					->from('accounts')
					->where(array('owner_id' => $owner_id, 'active' => 1));
			
			$query = $this->db->get();

			return $query->result();
		}

		public function getAccounts() {
			return $this->accounts;
		}
	}
?>
