<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	/**
	 * Description of UserDM
	 *
	 * @author stretch
	 */
	class Budget_DataModel_UserDM extends CI_Model {

		const TABLE_NAME = 'users';

		/**
		 * @var int
		 */
		private $id;

		/**
		 * @var string
		 */
		private $username;

		/**
		 * @var string
		 */
		private $password;

		/**
		 * @var string|null
		 */
		private $temp_pass;

		/**
		 * @var int
		 */
		private $temp_pass_active;

		/**
		 * @var string
		 */
		private $email;

		/**
		 * @var \DateTime
		 */
		private $date_added;

		/**
		 * @var int
		 */
		private $active;

		/**
		 * @var int
		 */
		private $level_access;

		/**
		 * @var string|null
		 */
		private $random_key;

		/**
		 * @var \DateTime
		 */
		private $agree_to_terms;

		/**
		 * @param mixed $where
		 */
		public function __construct($where = []) {
			parent::__construct();
			if(!empty($where)) {
				$this->load($where);
			}
		}

		/**
		 * @param mixed $where
		 * @throws Exception
		 */
		public function load($where) {
			$query = $this->db->get_where($where);

			if(!$query) {
				$error = $this->db->error();
				throw new Exception("unable to load user [".$error['message']."]");
			} elseif($query->num_rows() > 1) {
				throw new Exception('Too many users to load.');
			}

			$this->id = $query->row()->ID;
			$this->username = $query->row()->Username;
			$this->password = $query->row()->Password;
			$this->temp_pass = $query->row()->Temp_pass;
			$this->temp_pass_active = $query->row()->Temp_pass_active;
			$this->email = $query->row()->Email;
			$this->date_added = new DateTime($query->row()->dateAdded);
			$this->active = $query->row()->Active;
			$this->level_access = $query->row()->Level_access;
			$this->random_key = $query->row()->Random_key;
			$this->agree_to_terms = new DateTime($query->row()->agree_to_terms);
		}

		/**
		 * @return boolean
		 */
		public function save() {
			if($this->id && $this->rowExists()) {
				return $this->update();
			} else {
				return $this->insert();
			}
		}

		/**
		 * @return boolean
		 * @throws Exception
		 */
		private function insert() {
			$set = $this->buildSet();
			if($this->id) {
				$set->ID = $this->id;
			}
			if(!$this->db->insert(self::TABLE_NAME, $set)) {
				$error = $this->db->error();
				throw new Exception($error['message']);
			}

			return true;
		}

		/**
		 * @return boolean
		 * @throws Exception
		 */
		private function update() {
			$set = $this->buildSet();
			if($this->id) {
				$set->ID = $this->id;
			}
			if(!$this->db->update(self::TABLE_NAME, $set)) {
				$error = $this->db->error();
				throw new Exception($error['message']);
			}

			return true;
		}

		/**
		 * @return \stdClass
		 */
		private function buildSet() {
			$set = new stdClass();
			$set->Username = $this->username;
			$set->Password = $this->password;
			($this->temp_pass) ? $set->Temp_pass = $this->temp_pass : null;
			($this->temp_pass_active) ? $set->Temp_pass_active = $this->temp_pass_active : null;
			$set->Email = $this->email;
			($this->date_added) ? $set->dateAdded = $this->date_added->format('Y-m-d H:i:s') : null;
			$set->Active = $this->active;
			$set->Level_access = 2;
			($this->random_key) ? $set->Random_key = $this->random_key : null;
			$set->agree_to_terms = $this->agree_to_terms;

			return $set;
		}

		/**
		 * @return boolean
		 */
		private function rowExists() {
			$query = $this->get_where(self::TABLE_NAME, ['ID' => $this->id]);

			return ($query->num_rows() > 0);
		}

		/**
		 * @return int
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * @return string
		 */
		public function getUsername() {
			return $this->username;
		}

		/**
		 * @return string
		 */
		public function getPassword() {
			return $this->password;
		}

		/**
		 * @return string
		 */
		public function getTempPass() {
			return $this->temp_pass;
		}

		/**
		 * @return int
		 */
		public function getTempPassActive() {
			return $this->temp_pass_active;
		}

		/**
		 * @return string
		 */
		public function getEmail() {
			return $this->email;
		}

		/**
		 * @return \DateTime
		 */
		public function getDateAdded() {
			return $this->date_added;
		}

		/**
		 * @return int
		 */
		public function getActive() {
			return $this->active;
		}

		/**
		 * @return int
		 */
		public function getLevelAccess() {
			return $this->level_access;
		}

		/**
		 * @return string
		 */
		public function getRandomKey() {
			return $this->random_key;
		}

		/**
		 * @return \DateTime
		 */
		public function getAgreeToTerms() {
			return $this->agree_to_terms;
		}

		/**
		 * @param string $value
		 * @return \Budget_DataModel_UserDM
		 */
		public function setUsername($value) {
			$this->username = $value;
			return $this;
		}

		/**
		 * @param string $value
		 * @return \Budget_DataModel_UserDM
		 */
		public function setPassword($value) {
			$this->password = password_hash($value, PASSWORD_BCRYPT);
			return $this;
		}

		/**
		 * @param string $value
		 * @return \Budget_DataModel_UserDM
		 */
		public function setTempPass($value) {
			$this->temp_pass = password_hash($value, PASSWORD_BCRYPT);
			return $this;
		}

		/**
		 * @param bool $value
		 * @return \Budget_DataModel_UserDM
		 */
		public function setTempPassActive($value) {
			$this->temp_pass_active = $value;
			return $this;
		}

		/**
		 * @param string $value
		 * @return \Budget_DataModel_UserDM
		 */
		public function setEmail($value) {
			$this->email = $value;
			return $this;
		}

		/**
		 * @param DateTime $value
		 * @return \Budget_DataModel_UserDM
		 */
		public function setDateAdded($value) {
			$this->date_added = $value;
			return $this;
		}

		/**
		 * @param bool $value
		 * @return \Budget_DataModel_UserDM
		 */
		public function setActive($value) {
			$this->active = $value;
			return $this;
		}

		/**
		 * @param int $value
		 * @return \Budget_DataModel_UserDM
		 */
		public function setLevelAccess($value) {
			$this->level_access = $value;
			return $this;
		}

		/**
		 * @param string $value
		 * @return \Budget_DataModel_UserDM
		 */
		public function setRandomKey($value) {
			$this->random_key = $value;
			return $this;
		}

		/**
		 * @param DateTime $value
		 * @return \Budget_DataModel_UserDM
		 */
		public function setAgreeToTerms(DateTime $value) {
			$this->agree_to_terms = $value;
			return $this;
		}
	}
