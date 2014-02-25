<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require_once(APPPATH . 'third_party/phpass-0.3/PasswordHash.php');

// add $this->auth->restrict() to any methods inside controllers.

class Auth {

	const PHPASS_ITERATIONS = 8;
	const PHPASS_PORTABLE_HASH = true; //we must have portable passwords or we'll be hosed when we move off of a hosting company... might even be hosed on the hosting company.
	const USER_LOCKOUT_THRESHOLD = 5;

	private $CI = NULL;
	private $index_redirect;
	private $login_redirect;
	private $inactive_site;

	function __construct($props = array()) {
		$this->CI = & get_instance();
		// Load additional libraries, helpers, etc.
		$this->CI->load->library('session');
		$this->CI->load->database();
		$this->CI->load->helper('url');

		if (count($props) > 0) {
			$this->initialize($props);
		}
	}

	/**
	 * processes the login
	 *
	 * @param string $username
	 * @param string $password
	 * @return mixed
	 */
	public function process_login($username, $password) {
		$phpass = new PasswordHash(self::PHPASS_ITERATIONS, self::PHPASS_PORTABLE_HASH);
		// Query time
		$this->CI->db->select('*');
		$this->CI->db->from('USERS');
		$this->CI->db->where('username', $username);
		$query = $this->CI->db->get();

		//we must have exactly one row.
		if ($query->num_rows > 1) {
			//too many users with this username
			$result = 'We are unable to process your request at this time, please try again later. If the problem persists please contact our customer service department.';
			log_message('error', 'more than one ' . $username . ' found in our system', false);
		} else if ($query->num_rows < 1) {
			//the user wasn't found
			$result = 'invalid username or password';
			log_message('error', $username . ' was not found in our system', false);
		} else if ($query->row()->locked_out == true) {
			$result = 'Your account has been locked out, please <a href="javascript:void(null)"> reset your password</a> to regain access';
		} else if ($phpass->CheckPassword($password, $query->row()->password) === false) {
			//the users password didn't match
			$result = 'invalid username or password';
			$this->updateLoginAttempts($username);
			log_message('error', $username . ' attempted to login with an invalid password', false);
		} else {
			// Our user exists and is not locked out, set session.
			$this->CI->session->set_userdata('username', $username);
			$this->CI->session->set_userdata('user_id', $query->row()->user_id);
			$this->CI->session->set_userdata('zip', $query->row()->zip);
			$this->CI->session->set_userdata('profile_dm_cache_key', CacheUtil::generateCacheKey('profile_dm_'));

			$result = true;
		}

		return $result;
	}

	/**
	 * updates login attempt count for failed logins
	 *
	 * @param type $username
	 * @return void
	 */
	public function updateLoginAttempts(&$username) {
		$data = array();
		$results = $this->CI->db->select('login_attempts')
			   ->from('USERS')
			   ->where('username', $username)
			   ->get();

		if ($results->row()->login_attempts == (self::USER_LOCKOUT_THRESHOLD - 1)) {
			$data['lockout'] = true;
			log_message('error', $username . ' has been locked out', false);
		}

		$data['login_attempts'] = $results->row()->login_attempts + 1;

		$this->CI->db->where('username', $username)
			   ->update('USERS', $data);
	}

	public function redirect() {
		if ($this->CI->session->userdata('redirected_from') == false) {
			redirect($this->index_redirect);
		} else {
			redirect($this->CI->session->userdata('redirected_from'));
		}
	}

	/**
	 * initialize class preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */
	public function initialize($props = array()) {
		if (count($props) > 0) {
			foreach ($props as $key => $val) {
				$this->$key = $val;
			}
		}
	}

	/**
	 *
	 * This function restricts users from certain pages.
	 * use restrict(TRUE) if a user can't access a page when logged in
	 *
	 * @access	public
	 * @param	boolean	wether the page is viewable when logged in
	 * @return	void
	 * @todo make this look for locked out.
	 */
	public function restrict($logged_out = FALSE) {
		//make sure the site is active
		$active = $this->isSiteActive();
		if (!$active) {
			$this->logout();
			redirect($this->inactive_site);
		}

		// if the users account has been locked redirect him to the login page
		if($this->lockedAccount() === true) {
			$this->logout();
		}

		// If the user is logged in and he's trying to access a page
		// he's not allowed to see when logged in,
		// redirect him to the index!
		if ($logged_out && $this->logged_in()) {
			redirect($this->index_redirect);
		}

		// If the user isn't logged in and he's trying to access a page
		// he's not allowed to see when logged out,
		// redirect him to the login page!
		if (!$logged_out && !$this->logged_in()) {
			$this->CI->session->set_userdata('redirected_from', $this->CI->uri->uri_string()); // We'll use this in our redirect method.
			redirect($this->login_redirect);
		}
	}

	/**
	 * check for a cached user profile dm, if exists check locked out property
	 *
	 * @return boolean
	 * @since 1.0
	 */
	private function lockedAccount() {
		$result = false;
		$profile_key = $this->CI->session->userdata('profile_dm_cache_key');
		if(!empty($profile_key)) {
			$cache_util = new CacheUtil();
			$user_profile_dm = unserialize($cache_util->fetchCache($this->CI->session->userdata('profile_dm_cache_key')));
			if(is_object($user_profile_dm) && $user_profile_dm instanceof UserProfileDM) {
				$result = Utilities::getBoolean($user_profile_dm->getLockedOut());
			}
		}

		return $result;
	}

	/**
	 *
	 * Checks if a user is logged in
	 *
	 * @access	public
	 * @return	boolean
	 */
	private function logged_in() {
		$result = true;

		//our user must have a username and a user_id
		if (!$this->CI->session->userdata('username') || !$this->CI->session->userdata('user_id')) {
			$result = false;
		}

		return $result;
	}

	public function logout() {
//		$this->updateLoginHistory(false, true);
		$this->CI->session->sess_destroy();
		redirect($this->login_redirect);
	}

	/**
	 * verify the site is active
	 *
	 * @access private
	 * @return boolean
	 */
	public function isSiteActive() {
		$result = true;
//		for now we are not going to worry about a site active rule
//		$content_db = $this->CI->load->database('content', true, true);
//		$where = array("site_id" => 'EC', "rule_name" => "IS_SITE_ACTIVE");
//		$results = $content_db->get_where("SITE_RULES", $where);
//		$rule = $results->row();
//
//		if ($rule->rule_value == "0") {
//			$result = false;
//		}

		return $result;
	}

}

// End of library class
// Location: system/application/libraries/Auth.php