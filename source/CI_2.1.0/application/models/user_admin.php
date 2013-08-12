<?php
class User_admin extends N8_Model {
		
	function User_admin(){
		parent::__construct();
	}

	function getUserProfile($user_id) {
		$query = $this->db->select("u.Username, u.Email, pl.payschedule_translation")
						->from("users u")
						->join("payschedule p", "p.ownerId = u.ID")
						->join("payschedule_lookup pl", "pl.payschedule_code = p.paySchedule")
						->where(array("u.ID" => $user_id))
						->get();

		return $query->row();
	}
}
?>
