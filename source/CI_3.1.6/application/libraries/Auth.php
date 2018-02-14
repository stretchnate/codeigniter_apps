<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// add $this->auth->restrict() to any methods inside controllers.

class Auth {
	var $CI 			= NULL;
	var $index_redirect	= '/';
	var $login_redirect	= '/admin/login';
	var $inactive_site  = "/inactive/";

	public function Auth($props = array()) {
		$this->CI =& get_instance();
		// Load additional libraries, helpers, etc.
		$this->CI->load->library('session');
		$this->CI->load->database();
		$this->CI->load->helper('url');
		if (count($props) > 0) {
			$this->initialize($props);
		}
	}

	public function process_login($login = NULL, $preverified = false) {
		// A few safety checks
		// Our array has to be set
		if(!isset($login))
			return false;

		//Our array has to have 2 values
		//No more, no less!
		if(count($login) != 2)
			return false;

		$username = $login[0];

		// Query time
		$this->CI->db->select('u.*, lh.*');
		$this->CI->db->from('users u');
		$this->CI->db->join('login_history lh', 'u.ID = lh.UserId', 'left');
		$this->CI->db->where('username', $username);
		$this->CI->db->order_by('DateLoggedIn', 'desc');
		$this->CI->db->limit(1);
		$query = $this->CI->db->get();

		if(!$query->num_rows()) {
			return false;
		}

		$verified = $preverified === true ? $preverified : password_verify($login[1], $query->row()->Password);

		if ($query->num_rows() == 1 && $verified) {
			// Our user exists, set session.
			$this->CI->session->set_userdata('logged_user', $username);
			$row = $query->row();
			$this->CI->session->set_userdata('user_id', $row->ID);
			$this->CI->session->set_userdata('last_login', $row->DateLoggedIn);
			if($row->LastUpdate) {
				$this->CI->session->set_userdata('last_update', $row->LastUpdate);
			} else {
				$result = $this->getLastUpdate($row->ID);

				if( count($result) > 0 && isset($result->LastUpdate) ) {
					$this->CI->session->set_userdata('last_update', $result->LastUpdate);
				}
			}
			$this->insertLoginHistory($row->ID);
			return TRUE;
		} else {
			// No existing user.
			return FALSE;
		}
	}

	/**
	* Returns last update date
	*/
	public function getLastUpdate($id) {
		$this->CI->db->select("*");
		$this->CI->db->from('login_history');
		$this->CI->db->where('UserId', $id);
		$this->CI->db->where('LastUpdate IS NOT NULL');
		$this->CI->db->order_by('DateLoggedIn', 'desc');
		$this->CI->db->limit(1);
		$query = $this->CI->db->get();
		return $query->row();
	}

	/**
	* begins a new login history
	* @param $id
	* @author dnate
	*/
	public function insertLoginHistory($id) {
		$array = array('UserId' => $id);
		$this->CI->db->insert('login_history', $array);
	}

	/**
	* Updates LastUpdate and DateLoggedOut
	* @param $update
	* @param $logout
	* @author dnate
	*/
	public function updateLoginHistory($update = FALSE, $logout = FALSE) {
		$data = array();
		if($update === TRUE) {
			$data['LastUpdate'] = date('Y-m-d H:i:s');
		}
		if($logout === TRUE) {
			$data['DateLoggedOut'] = date('Y-m-d H:i:s');
		}
		$this->CI->db->where('UserId', $this->CI->session->userdata('user_id'));
		$this->CI->db->where('DateLoggedOut', NULL, FALSE);
		$query = $this->CI->db->update('login_history', $data);
		if($query && $update === TRUE) {
			$this->CI->session->set_userdata('last_update', $data['LastUpdate']);
		}
	}

	public function redirect() {
		if ($this->CI->session->userdata('redirected_from') == FALSE) {
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
	 */
	public function restrict($logged_out = FALSE) {
		//make sure the site is active
		$active = $this->isSiteActive();
		if( !$active ) {
			$this->logout();
			redirect($this->inactive_site);
		}

		// If the user is logged in and he's trying to access a page
		// he's not allowed to see when logged in,
		// redirect him to the index!
		if ($logged_out && $this->logged_in()) {
			redirect($this->index_redirect);
		}

		// If the user isn' logged in and he's trying to access a page
		// he's not allowed to see when logged out,
		// redirect him to the login page!
		if ( ! $logged_out && ! $this->logged_in()) {
			$this->CI->session->set_userdata('redirected_from', $this->CI->uri->uri_string()); // We'll use this in our redirect method.
			redirect($this->login_redirect, 'location');
		}
	}

	/**
	 *
	 * Checks if a user is logged in
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function logged_in() {
		if ($this->CI->session->userdata('logged_user') == FALSE) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function logout() {
		$this->updateLoginHistory(FALSE, TRUE);
		$this->CI->session->unset_userdata('logged_user');
		$this->CI->session->unset_userdata('user_id');
		$this->CI->session->sess_destroy();
		return TRUE;
	}

	public function isSiteActive() {
		$where = array("rule_name" => "IS_SITE_ACTIVE");
		$results = $this->CI->db->get_where("rules", $where);
		$rule = $results->row();

		if($rule->rule_value == "0") {
			return FALSE;
		}
		return TRUE;
	}
}
// End of library class
// Location: system/application/libraries/Auth.php