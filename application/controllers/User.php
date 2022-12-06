<?php

class User extends N8_Controller {

	function __construct() {
		parent::__construct();
	}

	function index($edit = FALSE) {
		$this->auth->restrict();
		$this->load->helper('form');
		$this->load->model('User_admin', 'UA', TRUE);
		$data->profile = $this->UA->getUserProfile($this->session->userdata('user_id'));
		$data->profile->edit = $edit;
		$props['title'] = "User Profile";
		$props['scripts'] = $this->jsincludes->home();
		$props['links'] = $this->utilities->createLinks('main_nav');

		$this->load->view('header',$props);
		$this->load->view('userProfile',$data);
		$this->load->view('footer');
	}

	function edit() {
		$this->index(TRUE);
	}
}

/* End of file user.php */
/* Location: ./system/application/controllers/user.php */
