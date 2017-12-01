<?php

class UserCTL extends N8_Controller {

	protected $user_dm;

	function __construct() {
		parent::__construct();
		$this->load->helper('form');

		$this->user_dm = new Budget_DataModel_UserDM(['ID' => $this->session->userdata('user_id')]);
	}

	function index() {
		$this->auth->restrict();

		$user_profile_vw = $this->buildUserProfileView();
		$user_profile_vw->renderView();
	}

	function update() {
		$this->auth->restrict();
		$rules        = array();
		$show_success = false;

		$rules[] = array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'required|valid_email');

		$rules[] = array(
					'field' => 'confirm_new_password',
					'label' => 'Confirm New Password',
					'rules' => 'matches[new_password]');

		$rules[] = array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'required');

		$this->user_dm->setEmail($this->input->post('email'));

		if($this->validate($rules) === true) {
			//update the changes to the db
			if(!password_verify($this->input->post('new_password'), $this->user_dm->getPassword())) {
				$this->user_dm->setPassword($this->input->post('new_password'));
			}

			if($this->user_dm->save()) {
				$show_success = "Update was successful";
			}
		}

		$user_profile_vw = $this->buildUserProfileView();
		$user_profile_vw->setErrors(array($show_success));
		$user_profile_vw->generateView();
	}

	private function buildUserProfileView() {
		$props['title'] = "User Profile";
		$props['scripts'] = Jsincludes::getUserProfileJS();
		$props['links'] = $this->utilities->createLinks('main_nav');

		$this->load->view('budget/userProfile/userProfileVW');

		$CI =& get_instance();
		$user_profile_vw = new Budget_UserProfile_UserProfileVW($CI);
		$user_profile_vw->setUserDM($this->user_dm);

		$user_profile_vw->setTitle($props['title']);//these 3 are in baseVW.php
		$user_profile_vw->setScripts($props['scripts']);

		return $user_profile_vw;
	}
}

/* End of file user.php */
/* Location: ./system/application/controllers/user.php */
