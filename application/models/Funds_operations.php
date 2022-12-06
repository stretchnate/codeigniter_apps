<?php
class Funds_operations extends N8_Model {

	function __construct(){
            parent::__construct();
	}

	function insertMain($id,$data){
		$query = $this->db->insert('new_funds', $data);
		return $this->db->insert_id();
	}

	/**
	 * Gets the pay schedule code from the accounts table (used to get it from payschedule but that id depricated now that we support multiple accounts)
	 *
	 * @param owner_id String
	 * @param account_id String
	 * @return Object
	 */
	function getSchedule($owner_id, $account_id){
		// $query = $this->db->query('SELECT paySchedule FROM paySchedule WHERE ownerId ='.$owner_id);
		$query = $this->db->select("payschedule_code")
							->get_where("accounts", array("owner_id" => $owner_id, "account_id" => $account_id));
		$schedule = $query->row();
		return $schedule;
	}

    /**
     * @param $account
     * @param $amount
     * @return bool
     */
	function setAccountAmount($account,$amount) {
		//update account with new current amount
		$data = array('bookAmtCurrent' => $amount);
		$this->db->where('bookId',$account);
		$query = $this->db->update('booksummary', $data);
		if(!$query) {
			return false;
		}
		return true;
	}

	function transferToAccount($id,$amount) {
		//get account amount
		$accountAmount = $this->getAccountAmount($id);

		//add transfer amount to current amount
		$accountAmount = $accountAmount + $amount;

		//update account with new amount
		$data = array('bookAmtCurrent' => $accountAmount);
		$this->db->where('bookId',$id);
		$query = $this->db->update('booksummary',$data);
		if(!$query) {
			return false;
		}
		return true;
	}

    /**
     * @param $id
     * @return mixed
     */
	function getAccountAmount($id) {
		$query = $this->db->get_where('booksummary',array('bookId' => $id));
		$row = $query->row();
		return $row->bookAmtCurrent;
	}
}
?>
