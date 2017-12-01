<?php
class Admin_model extends N8_Model {

	function Admin_model(){
			parent::__construct();
	}

	public function checkExisting() {
		$data = array('Username' => $this->input->post('username'));
		$query = $this->db->get_where('users',$data);
		$num = $query->num_rows();
		return $num;
	}

	/**
	 * Creates a new user account upon registration
	 *
	 */
	public function createUser($data) {
		$result = false;
		$date = date("Y-m-d H:i:s");
		$user_info = array('Username' => $data['username'],
							'Password' => password_hash($data['password']),
							'Email' => $data['email'],
							'agree_to_terms' => $date,
							'dateAdded' => $date);
		if($this->db->insert('users',$user_info)) {
			$query = $this->db->get_where('users',$user_info);//get user id.

			if(!$query || $query->num_rows() < 1) {
				$error = $this->db->error();
				throw new Exception('ERROR: '.$error['message']);
			}

			$row = $query->row();
			$result = $row->ID;
		}

		return $result;
	}

	public function createCharitableAcct($id) {
		$account_info = array('CA_ID' => $id);
		if(!empty($_POST['caName']))
			$account_info['CA_Name'] = $this->input->post('caName');
		else
			$account_info['CA_Name'] = 'CharitableAccount_'.$id;

		if($_POST['calc'] != 3) {
			$account_info['multiplier'] = $this->input->post('multiplier');
			$account_info['priority'] = $this->input->post('priority');
		}
		$account_info['MultiplierType'] = $this->input->post('calc');
		$account_info['active'] = 1;
		try {
			$this->db->insert('charitableaccounts',$account_info);
		} catch(Exception $e) {
			return 0;
			//TODO: log $e here
		}
		return 1;
	}
}
