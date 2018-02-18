<?php
class Deposits extends N8_Model {

	function __construct(){
            parent::__construct();
	}

	function getDeposits($user_id, $account_id, $start_date, $end_date = null, $offset = 0, $rowsPerPage = 100) {

		$pattern = "/[\d]{2}:[\d]{2}:[\d]{d}/";
		if($end_date == null) {
			$end_date = date("Y-m-d H:i:s");
		} else {
			if( !preg_match($pattern, $end_date) ) {
				$end_date = date("Y-m-d 23:59:59", strtotime($end_date));
			}
		}

		if( !preg_match($pattern, $end_date) ) {
			$start_date = date("Y-m-d 00:00:00", strtotime($start_date));
		}

		$where = "nf.ownerId = {$user_id}
				AND nf.account_id = {$account_id}
				AND nf.date BETWEEN '".$start_date."' AND '".$end_date."'";
		$query = $this->db->select("a.account_name, nf.*", false)
							->from('new_funds nf')
							->join('accounts a', 'nf.account_id = a.account_id AND nf.ownerId = a.owner_id')
							->where($where)
							->order_by('date', 'desc')
							->limit($rowsPerPage, $offset)
							->get();

		return $query->result();
	}
}
?>
