<?php

	/**
	 * The user data model
	 *
	 * @author dnate
	 * @since 2013.02.02
	 * @deprecated
	 */
	class UserDM extends N8_Model {

		protected $user_id;
		protected $username;
		protected $email;

		private   $password;

		function __construct(){
			parent::__construct();
		}

		/**
		 * This method loads user details into this class
		 *
		 * @return void
		 * @since 2013.02.02
		 */
		public function loadUserProfile() {
			$query = $this->db->select("Username, Email, Password")
							->where("ID", $this->user_id)
							->get("users");

			$this->username = $query->row()->Username;
			$this->email    = $query->row()->Email;
			$this->password = $query->row()->Password;
		}

		public function save() {
			return $this->updateUserProfile();
		}

		private function updateUserProfile() {
			$data   = array();
			$result = true;

			if($this->user_id > 0) {
				$data['Username'] = $this->getUsername();
				$data['Email']    = $this->getEmail();
				$data['Password'] = $this->getPassword();

				$this->db->where("ID", $this->user_id);
				$this->db->update('users', $data);
			} else {
				$result = false;
			}

			return $result;
		}

		public function setUserId($user_id) {
			$this->user_id = $user_id;
		}

		public function getUserId() {
			return $this->user_id;
		}

		public function getUsername() {
			return $this->username;
		}

		public function getEmail() {
			return $this->email;
		}

		public function setEmail($email) {
			$this->email = $email;
		}

		public function setPassword($password) {
			$this->password = password_hash($password);
		}

		public function getPassword() {
			return $this->password;
		}
	}
?>
