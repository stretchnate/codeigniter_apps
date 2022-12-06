<?php
class Accounts extends Book_info {

	function __construct(){
		parent::__construct();
	}

	/**
	 * returns data for a single account
	 */
	function getAccount($account_id) {
		$query = $this->db->get_where("accounts", array("account_id" => $account_id));
		return $query->row();
	}

	/**
	 * updates account info
	 */
	function saveAccount($account) {
		if(!is_object($account)) {
			return false;
		}

		$sets = array();
		$current_data = $this->getAccount($account->account_id);

		if($account->account_name != $current_data->account_name) {
			$sets["account_name"] = $account->account_name;
		}

		if($account->account_amount != $current_data->account_amount) {
			$sets["account_amount"] = $account->account_amount;
		}

		if($account->payschedule_code != $current_data->payschedule_code) {
			$sets["payschedule_code"] = $account->payschedule_code;
		}

		if($account->active != $current_data->active) {
			$sets["active"] = $account->active;
		}

		if( count($sets) > 0 ) {
			$this->db->where('account_id',$account->account_id);
			$query = $this->db->update('accounts', $sets);
			if(!$query) {
				return false;
			}
		}
		return true;
	}

	function getAccounts($id) {
		$query = $this->db->get_where('accounts',array('owner_id' => $id, 'active' => 1));
		return $query->result();
	}

	// function getAccountsAndCategories($id) {
		// $accounts_and_categories = array();
		// $accounts = $this->getAccounts($id);

		// foreach($accounts as $account) {
			// $account->categories = $this->getAllAccounts($account->account_id);
			// $accounts_and_categories[$account->account_name] = $account;
		// }

		// return $accounts_and_categories;
	// }

	function getAccountsAndDistributableCategories($id) {
		$accounts = $this->getAccounts($id);

		$accounts_and_categories = array();
		foreach($accounts as $account) {
			$account->categories = $this->getAccountsInfo($id, $account->account_id);
			$accounts_and_categories[$account->account_name] = $account;
		}

		return $accounts_and_categories;
	}

	function createNewAccount($account_name, $pay_schedule, $owner) {
		$select_data = array('account_name' => $account_name,'owner_id' => $owner);
		$query = $this->db->get_where("accounts", $select_data);

		if($query->num_rows() < 1) {
			$data = array('account_name' => $account_name,
						'owner_id' => $owner,
						'payschedule_code' => $pay_schedule,
						'active' => 1);
			$query = $this->db->insert("accounts",$data);
			if($query) {
				$query = $this->db->get_where("accounts", $select_data);
				if($query) {
					$row = $query->row();
					$return_data['num_rows'] = $query->num_rows();
					$return_data['new_account_id'] = $row->account_id;
					return $return_data;
				}
			}
		}
		return false;
	}

	function checkExistingAccount($owner_id, $account_name) {
		$data = array('account_name' => $account_name,'owner_id' => $owner_id);
		$query = $this->db->get_where('accounts',$data);
		$num = $query->num_rows();
		return $num;
	}
}
?>
