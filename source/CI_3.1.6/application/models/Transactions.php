<?php
//to be replaced by budget/TransactionDM
class Transactions extends N8_Model {
		
	function __construct(){
            parent::__construct();
	}

	function transactionClearedBank($id) {
		$query = $this->db->get_where('cleared_transactions', array('transactionId' => $id, 'end_date' => NULL));
		$this->db->trans_start();
		if( count($query->result()) > 0 ) {
			$this->db->where("transactionId = $id AND end_date IS NULL");
			$this->db->set("end_date", "NOW()", FALSE);
			$this->db->update('cleared_transactions');
			$status = "off";
		} else {
			$data = array('transactionId' => $id, 'cleared_bank' => TRUE);

			$this->db->insert('cleared_transactions', $data);
			$status = "on";
		}
		$this->db->trans_complete();
		if( $this->db->trans_status() === FALSE ) {
			$status = FALSE;
		}
		return $status;
	}

	function getLastTransaction($bucketId, $owner_id) {
		$query = $this->db->select("t.transactionId, t.TransType, t.bookTransAmt, b.bookId, b.bookName")
						->from("booktransactions t")
						->join("booksummary b", "b.bookId = t.bookId")
						->where("t.bookId != '{$bucketId}' AND t.ownerId = {$owner_id}")
						->order_by("transactionId", "desc")
						->limit(1)
						->get();

		return $query->row();
	}

	function getTransactionsByDate($ownerId, $dates, $offset = 0, $rowsPerPage = 20, $bookId = null) {
		if($dates['end_date'] == '') {
			$dates['end_date'] = date("Y-m-d");
		}
		$where = "t.ownerId = {$ownerId} AND bookTransDate BETWEEN '{$dates['start_date']} 00:00:00' AND '{$dates['end_date']} 23:59:59'";
		if( !empty($bookId) ) {
			$where .= " AND t.bookId = ".$bookId;
		}
		$where .= " AND ct.end_date IS NULL";

		$query = $this->db->select('t.*,b.bookName, ct.date_added AS `cleared`')
							->from('booktransactions t')
							->join('booksummary b','b.bookId = t.bookId', 'left')
							->join('cleared_transactions ct', 't.transactionId = ct.transactionId', 'left')
							->where($where)
							->order_by('bookTransDate','desc')
							->limit($rowsPerPage, $offset)
							->get();

		return $query->result();
	}
}
?>
