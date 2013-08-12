<?php
class Book_info extends N8_Model {
		
	function Book_info(){
		parent::__construct();
	}

	function getAccountIds($id){
		$this->db->select('bookId');
		$this->db->order_by('priority');
		$query = $this->db->get_where('booksummary',array('ownerId' => $id, 'active' => 1));
		$i = 0;
		foreach($query->result() as $result){
			$book['bookId'.$i] = $result->bookId;
			$i++;
		}
		return $book;
	}
	
	function getAccountsInfo($owner_id, $account_id){
		$data = array();
		$query = $this->db->select('bs.*', FALSE)
							->select('acct.payschedule_code', FALSE)
							->from('booksummary bs')
							->join('accounts acct', 'acct.account_id = '.$account_id, 'inner')
							->where(array('bs.ownerId' => $owner_id, 'bs.account_id' => $account_id,'bs.active' => 1), null, FALSE)
							->order_by('priority','asc')
							->order_by('due_day', 'asc')
							->get();

		return $query->result();
	}
	
	/**
	 * returns all categories including bucket and any charitable account.
	 * @return false if no bucket, or categories array
	 *
	 */
	function getAllAccounts($account_id) {
		$categories = $this->getAccountsInfo($this->session->userdata('user_id'), $account_id);

		$bucket = $this->getBucket($this->session->userdata('user_id'));
		if($bucket) {
			array_splice($categories,0,0,array($bucket));
//			$categories['charitable'] = $this->getCharitableAccount($this->session->userdata('user_id'));
			return $categories;
		}
		return false;
	}
	
	/**
	 * gets the bucket account only
	 * @return $response array
	 */
	function getBucket($bucketId) {
		$query = $this->db->get_where('_buckets',array('bucketId' => $bucketId, 'active' => 1));
		if($query->num_rows() == 1) {
			$response = $query->row();
		} else {
			$response = false;
		}
		return $response;
	}
	
	/**
	 * gets charitable account only
	 * @return $response array
	 */
	function getCharitableAccount($CA_ID = 0) {
		$query = $this->db->get_where('charitableaccounts', array('CA_ID' => $CA_ID, 'active' => 1));
		if($query->num_rows() == 1) {
			$response = $query->row();
		} else {
			$response = false;
		}
		return $response;
	}

	//@TODO TURN THIS INTO A BUSINESS MODEL THAT LOAD A DATA MODEL FOR EACH TRANSACTION.
	function getUserTransactions($bookId = null, $ownerId, $offset = 0, $rowsPerPage = 20) {
//		$limit = $this->db->limit($offset, $rowsPerPage);
//		$query = $this->db->get_where('booktransactions', array('ownerId' => $this->session->userdata('user_id')),$limit);
		$sql = "SELECT t.*,b.bookName, ct.date_added AS `cleared` from booktransactions t 
				LEFT JOIN booksummary b on (b.bookId = t.bookId)
				LEFT JOIN cleared_transactions ct ON (t.transactionId = ct.transactionId AND ct.end_date IS NULL)";

		$where = " WHERE t.ownerId = $ownerId";
		$order = " ORDER BY transactionId DESC LIMIT $offset,$rowsPerPage";
		if(!empty($bookId)) {
			$where .= " AND t.bookId = $bookId";
		}

		$sql .= $where;
		$sql .= $order;

		$row = array();
		$query = $this->db->query($sql);
		foreach ($query->result() as $result) {
			$row[] = $result;
		}
		if(count($row) < 1 || !isset($row)) {
			return false;
		}
		return $row;
	}

	//TODO make this more dynamic (not use post only)
	function addCategory($ownerId, $account_id){
		$data = array('bookName' => $this->input->post('name'),
					'bookAmtNec' => $this->input->post('nec'),
					'bookAmtCurrent' => $this->input->post('startAmt'),
					'InterestBearing' => $this->input->post('interest'),
					'priority' => $this->input->post('priority'),
					'ownerId' => $ownerId,
					'due_day' => $this->input->post('dueDay'),
					'account_id' => $account_id);
		$query = $this->db->insert("booksummary",$data);
		if($query) {
			$data = "";
			$data = array('bookName' => $this->input->post('name'),'ownerId' => $ownerId, 'account_id' => $account_id);
			$query = $this->db->get_where("booksummary", $data);
			if($query) {
				$row = $query->row();
				$data['num_rows'] = $query->num_rows();
				$data['newId'] = $row->bookId;
				return $data;
			}
		}
		return false;
	}
	
	/**
	 * formerly getInfo selects all rows from booksummary for any one account(book).
	 *
	 */
	function getAccountData($id) {
		$query = $this->db->get_where('booksummary',array('bookId' => $id));
		$row = $query->row();
		return $row;
	}
	
	function setInterest($id, $rate, $rateType, $amtOwed) {
		$data = array('bookID' => $id, 'rate' => $rate,'rate-type' => $rateType, 'amtOwed' => $amtOwed);
		$query = $this->db->insert('interest',$data);
		if(!$query)
			$result = 0;
		else 
			$result = 1;
		return $result;
	}
	
	function checkExisting($ownerId, $account_id, $category_name) {
		$data = array('bookName' => trim($category_name),'ownerId' => $ownerId, 'account_id' => $account_id);
		$query = $this->db->get_where('booksummary',$data);
		$num = $query->num_rows();
		return $num;
	}

	function delete_record($table,$id) {//the CI delete can accept an array of tables
		/**  EXAMPLE
		 * $tables = array('table1', 'table2', 'table3');
		 * $this->db->where('id', '5');
		 * $this->db->delete($tables);
		 */
		$this->db->where($id);
		$this->db->delete($table);
	}
	
	public function newTransaction($array) {
		$query = $this->db->insert('booktransactions', $array);
		if(!$query) {
			return false;
		}
		return true;
	}
}
?>
