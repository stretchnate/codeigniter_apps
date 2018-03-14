<?php

class Admin extends N8_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

	}

	public function profile() {
		$this->auth->restrict();
		$header_data['links'] = $this->utilities->createLinks('main_nav');

		$this->load->view('header', $header_data);
		$this->load->view('profile');
		$this->load->view('footer');
	}

	public function login() {
		if (!$this->auth->isSiteActive()) {
			redirect("/inactive/");
		}

		if ($this->input->post('username')) {
			$login = array($this->input->post('username', true), $this->input->post('password', true));
			if ($this->auth->process_login($login)) {
				// Login successful, let's redirect.
				$this->auth->redirect();
			} else {
				$data['error'] = 'Invalid Username or Password';
				$this->load->vars($data);
			}
		}

        $data['footer_nav'] = new NavigationUlLIB('footer');
		$this->load->view('login', $data);
	}

	public function logout() {
		$this->auth->logout();
		redirect(COMPANY_LOGOUT_REDIRECT);
	}

	public function register() {
		$this->load->view('registerView');
	}

	/**
	 * This function sets up new user accounts
	 */
	public function registerUser() {
		$data['error'] = "Unable to add user, please try again.";
		$rules = array(
			array(
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'trim|required|min_length[4]'
			),
			array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required|alpha_numeric|min_length[6]'
			),
			array(
				'field' => 'confirm_password',
				'label' => 'Confirm Password',
				'rules' => 'trim|required|matches[password]'
			),
			array(
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|valid_email'
			),
			array(
				'field' => 'agree_to_terms',
				'label' => 'Terms and Conditions',
				'rules' => 'callback_agreeToTerms'
			)
		);

		if($_POST['charitable'] == 1) {
			$rules['caName'] = 'trim';
			if ($_POST['calc'] < 3) {//3 = manually
				$rules['multiplier'] = 'trim|required|numeric';
			}
		}

		if($this->validate($rules)) {
			$this->load->model('Admin_model','Admin',TRUE);
			$create_user = $this->Admin->createUser($this->input->post());//create our user login in the db
			if($create_user) {
				$data['error'] = "Account Successfully Created.";
				//account created, log user in.
				$login = array($this->input->post('username'), $this->input->post('password'));
				if ($this->auth->process_login($login)) {
					// Login successful, let's redirect.
					$this->auth->redirect();
				}
			}
		} else {
			$data['error'] = $this->form_validation->error_string();
			$this->load->view('registerView',$data);
		}
	}

	public function checkName() {
		$response['success'] = false;
		$response['message'] = "Enter a valid username";
		if (!empty($_POST['username'])) {
			$this->load->model('admin_model', 'Admin', TRUE);
			$check = $this->Admin->checkExisting(); // check for exisiting account
			if ($check > 0) {
				$response['message'] = " this username already exists, please try another username.";
			} else {
				$response['success'] = true;
				$response['message'] = " this username is available";
			}
		}
		echo json_encode($response);
	}

	/**
	 * callback function for validating agree to terms checkbox
	 * @return boolean
	 */
	public function agreeToTerms() {
		$response = ($this->input->post('agree_to_terms') == 'on'
					|| $this->input->post('agree_to_terms') == 'checked'
					|| $this->input->post('agree_to_terms') === true);

		if(!$response) {
			$this->form_validation->set_message( 'agreeToTerms', 'You must agree to the Terms and Conditions' );
		}

		return $response;
	}
}

