<?php
class MaintenanceMDL extends N8_Model {
		
	function __construct(){
            parent::__construct();
	}

	function getTransactions() {
		$this->db->order_by("bookTransDate", "asc");
		$query = $this->db->get("booktransactions");
		return $query->result();
	}

	// function updateTransaction(&$transaction) {
		// $sets = array();
		// foreach($transaction as $column => $value) {
			// switch($column) {
				// case "to_category":
					// $sets["to_category"] = $value;
					// break;
					
				// case "from_category":
					// $sets["from_category"] = $value;
					// break;

				// case "to_account":
					// $sets["to_account"] = $value;
					// break;

				// case "from_account":
					// $sets["from_account"] = $value;
					// break;
			// }
		// }

		// if(count($sets) > 0) {
			// $this->db->where("transactionId", $transaction->transactionId);
			// $this->db->update("booktransactions", $sets);
		// }
	// }

	function updateClearedTransactions($old_id, $new_id) {
		$data = array('transactionId' => $new_id);

		$this->db->where('transactionId', $old_id);
		$this->db->update('cleared_transactions', $data); 
	}
}
?>
