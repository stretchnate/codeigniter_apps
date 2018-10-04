<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Transaction\Grid;

/**
 * Description of Helper
 *
 * @author stretch
 */
class Helper extends \CI_Model {
	private $user_id;

	/**
	 * @param type $user_id
	 */
	public function __construct($user_id) {
		parent::__construct();
		$this->user_id = $user_id;
	}

	/**
	 * @param type $limit
	 * @param type $offset
	 * @param type $transaction_type
	 * @param type $transaction_parent_id
	 * @return type
	 * @throws \Exception
	 */
	public function getPage($limit, $offset, $transaction_type, $transaction_parent_id) {
		$transaction_parent = $this->loadTransactionParent($transaction_type, $transaction_parent_id);

		$this->db->where($this->buildWhere($transaction_type, $transaction_parent), null, false)
			->order_by("transaction_id", "desc")
			->limit($limit, $offset);

		$query = $this->db->get("transactions");

		if(!$query) {
			throw new \Exception($this->db->error()['message']);
		}

		return $this->filterData($query->result(), $transaction_parent);
	}

	/**
	 * @param type $transaction_type
	 * @param type $transaction_parent_id
	 * @return type
	 * @throws \Exception
	 */
	public function getTotalRecords($transaction_type, $transaction_parent_id) {
		$transaction_parent = $this->loadTransactionParent($transaction_type, $transaction_parent_id);
		$this->db->select('count(*) as total_records', false);

		$this->db->where($this->buildWhere($transaction_type, $transaction_parent), null, false);

		$query = $this->db->get("transactions");

		if(!$query) {
			throw new \Exception($this->db->error()['message']);
		}

		return $query->row();
	}

	/**
	 * @param type $result
	 * @param type $transaction_parent
	 */
	private function filterData($result, $transaction_parent) {
		$filtered_results = [];
		foreach($result as $transaction) {
			$add_subtract = null;
			$new_row = new \stdClass();

			if($transaction_parent) {
				if($transaction_parent->getID() == $transaction->to_category || $transaction_parent->getID() == $transaction->to_account) {
					$add_subtract = "add";
				} else if($transaction_parent->getID() == $transaction->from_category || $transaction_parent->getID() == $transaction->from_account) {
					$add_subtract = "subtract";
				}
			}

			$new_row->transaction_id = $transaction->transaction_id;
			$new_row->transaction_amount = "<span class='$add_subtract'>".number_format($transaction->transaction_amount, 2, ".", ",")."</span>";
			$new_row->transaction_info = "<span class='optional'>$transaction->transaction_info</span>";

			$date = date("m/d", strtotime($transaction->transaction_date));
			$date .= "<span class='year'>".date("/Y", strtotime($transaction->transaction_date))."</span>";
			$new_row->transaction_date = $date;
			$new_row->delete_transaction = '';

			if($transaction->to_category || $transaction->from_category) {
                $new_row->delete_transaction = "<img src='/images/small_red_ex.png' alt='delete transaction {$transaction->transaction_id}' title='delete transaction {$transaction->transaction_id}' class='delete_transaction' />";
            }

			$filtered_results[] = $new_row;
		}

		return $filtered_results;
	}

	/**
	 * @param type $transaction_type
	 * @param type $transaction_parent_id
	 * @return type
	 */
	private function buildWhere($transaction_type, $transaction_parent_id) {
		$where = "transactions.owner_id = ".$this->user_id;

		$transaction_parent = $this->loadTransactionParent($transaction_type, $transaction_parent_id);

		if($transaction_type == "account") {
			$where .= " AND (to_account = {$transaction_parent->getID()} OR from_account = {$transaction_parent->getID()})";
		} else if($transaction_type == "category") {
			$where .= " AND (to_category = {$transaction_parent->getID()} OR from_category = {$transaction_parent->getID()})";
		}

		return $where;
	}

	/**
	 * @param type $transaction_type
	 * @param type $transaction_parent
	 * @return \Transaction\Grid\Budget_DataModel_CategoryDM|\Transaction\Grid\Budget_DataModel_AccountDM
	 */
	private function loadTransactionParent($transaction_type, $transaction_parent) {
		if( is_object($transaction_parent) ) {
			return $transaction_parent;
		} else {
			switch($transaction_type) {
				case "account":
					return new \Budget_DataModel_AccountDM($transaction_parent, $this->user_id);
				case "category":
				default:
					return new \Budget_DataModel_CategoryDM($transaction_parent, $this->user_id);
			}
		}
	}
}
