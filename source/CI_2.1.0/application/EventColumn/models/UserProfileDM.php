<?php

	/**
	 * This model represents a user Profile from the USERS database
	 *
	 * @author stretch
	 */
	class UserProfileDM extends BaseDM {

		const ACCESS_LEVEL_USER = 1;//access level for all users
		const MAX_LOGIN_ATTEMPTS = 5;

		private $user_id;
		private $email;
		private $username;
		private $password;
		private $temporary_password;
		private $account_active;
		private $access_level;
		private $date_added;
		private $zip;
		private $agree_to_terms;
		private $login_attempts;
		private $locked_out;
		private $locked_out_reason;

		public function __construct() {
			$this->access_level = self::ACCESS_LEVEL_USER;
		}

		/**
		 * loads the DM with the user profile data
		 *
		 * @param  int $user_id
		 * @return object UserProfileDM
		 * @since  1.0
		 */
		public function load( $user_id ) {
			$query = $this->db->get_where( "USERS", array( "user_id" => $user_id ) );

			if($query->num_rows > 0) {
				$result = $query->row();

				$this->user_id			 = $result->user_id;
				$this->email			 = $result->email;
				$this->username			 = $result->username;
				$this->password			 = $result->password;
				$this->temporary_password = $this->setTemporaryPassword($result->temporary_password);
				$this->account_active	 = $result->account_active;
				$this->access_level      = $result->access_level;
				$this->date_added		 = $result->date_added;
				$this->zip				 = $result->zip;
				$this->agree_to_terms	 = $result->agree_to_terms;
				$this->login_attempts	 = $result->login_attempts;
				$this->locked_out		 = $result->locked_out;
				$this->locked_out_reason = $result->locked_out_reason;
			}

			return $this;
		}

		/**
		 * saves the user profile to the database
		 *
		 * @return type
		 * @since  1.0
		 * @throws Exception
		 */
		public function save() {
			if( $this->user_id ) {
				$result = $this->update();
			} else {
				$result = $this->insert();
			}

			if( $result === false ) {
				throw new Exception( "There was a problem saving the User Profile" );
			} else {
				return $result;
			}
		}

		/**
		 * updates the user in USERS
		 *
		 * @return boolean
		 * @since 1.0
		 */
		protected function update() {
			$sets						 = array( );

			$sets['email']				 = $this->email;
			$sets['username']			 = $this->username;
			$sets['password']			 = $this->password;

			if($this->temporary_password === true) {
				$sets['temporary_password'] = $this->temporary_password;
			}

			$sets['account_active']		 = $this->account_active;
			$sets['access_level']	     = $this->access_level;
			$sets['zip']				 = $this->zip;
			$sets['agree_to_terms']		 = $this->agree_to_terms;
			$sets['login_attempts']		 = $this->login_attempts;
			$sets['locked_out']			 = $this->locked_out;
			$sets['locked_out_reason']   = $this->locked_out_reason;

			$this->db->where( "user_id", $this->user_id );
			return $this->db->update( "USERS", $sets );
		}

		/**
		 * inserts a new USERS record in the database.
		 *
		 * @return boolean
		 * @since  1.0
		 */
		protected function insert() {
			$values						 = array( );

			$values['username']			 = $this->username;
			$values['email']			 = $this->email;
			$values['password']			 = $this->password;

			if($this->temporary_password === true) {
				$values['temporary_password'] = $this->temporary_password;
			}

			$values['zip']				 = $this->zip;
			$values['agree_to_terms']	 = $this->agree_to_terms;

			return $this->db->insert( "USERS", $values );
		}

		/**
		 * loads a profile from an email address, used for password recovery
		 *
		 * @throws Exception
		 */
		public function loadProfileByEmail() {
			$query = $this->db->get_where('USERS', array('email' => $this->email));

			if($query->num_rows > 0) {
				if($query->num_rows > 1) {
					throw new Exception('multiple users with same email address');
				} else {
					$result = $query->row();

					$this->user_id			 = $result->user_id;
					$this->email			 = $result->email;
					$this->username			 = $result->username;
					$this->password			 = $result->password;
					$this->temporary_password = $this->setTemporaryPassword($result->temporary_password);
					$this->account_active	 = $result->account_active;
					$this->access_level      = $result->access_level;
					$this->date_added		 = $result->date_added;
					$this->zip				 = $result->zip;
					$this->agree_to_terms	 = $result->agree_to_terms;
					$this->login_attempts	 = $result->login_attempts;
					$this->locked_out		 = $result->locked_out;
					$this->locked_out_reason = $result->locked_out_reason;
				}
			}
		}

		/**
		 * validate user entered password against what we have in the db
		 *
		 * @param  string $password
		 * @return boolean
		 * @since 1.0
		 */
		public function checkUserPassword($password) {
			require_once(APPPATH . 'third_party/phpass-0.3/PasswordHash.php');

			$phpass = new PasswordHash(Auth::PHPASS_ITERATIONS, Auth::PHPASS_PORTABLE_HASH);
			return $phpass->CheckPassword( $password, $this->getPassword() );
		}

		/**
		 * increases login attempts and sets locked_out if user has had too many failed login attempts
		 * caches this object if locked_out is true.
		 *
		 * @return void
		 * @since 1.0
		 */
		public function increaseLoginAttempts() {
			$this->login_attempts = $this->login_attempts + 1;

			if($this->login_attempts >= self::MAX_LOGIN_ATTEMPTS) {
				$this->locked_out = true;
				$this->locked_out_reason = "too many failed login attempts";
				$this->save();
				$cache_util = new CacheUtil();
				$cache_util->saveCache($this->session->userdata('profile_dm_cache_key'), serialize($this), 3600);
			}
		}

		public function setEmail( $email ) {
			$this->email = $email;
			return $this;
		}

		public function setUsername( $username ) {
			$this->username = $username;
			return $this;
		}

		public function setPassword( $password ) {
			$this->password = $password;
			return $this;
		}

		public function setTemporaryPassword( $temporary_password ) {
			$this->temporary_password = Utilities::getBoolean($temporary_password);
			return $this;
		}

		public function setZip( $zip ) {
			$this->zip = $zip;
			return $this;
		}

		public function getEmail() {
			return $this->email;
		}

		public function getUsername() {
			return $this->username;
		}

		public function getPassword() {
			return $this->password;
		}

		public function getTemporaryPassword() {
			return $this->temporary_password;
		}

		public function getZip() {
			return $this->zip;
		}

		public function setAccountActive( $account_active ) {
			$this->account_active = Utilities::getBoolean( $account_active );
			return $this;
		}

		public function getAccountActive() {
			return $this->account_active;
		}

		public function setAccessLevel( $access_level ) {
			$this->access_level = $access_level;
			return $this;
		}

		public function getAccessLevel() {
			return $this->access_level;
		}

		public function setDateAdded( $date_added ) {
			$this->date_added = strtotime( $date_added );
			return $this;
		}

		public function getDateAdded() {
			return $this->date_added;
		}

		public function setAgreeToTerms( $agree_to_terms ) {
			$this->agree_to_terms = Utilities::getBoolean( $agree_to_terms );
			return $this;
		}

		public function getAgreeToTerms() {
			return $this->agree_to_terms;
		}

		public function setLoginAttempts( $login_attempts ) {
			$this->login_attempts = $login_attempts;
			return $this;
		}

		public function getLoginAttempts() {
			return $this->login_attempts;
		}

		public function setLockedOut( $locked_out ) {
			$this->locked_out = $locked_out;
			return $this;
		}

		public function getLockedOut() {
			return $this->locked_out;
		}

	}

?>
