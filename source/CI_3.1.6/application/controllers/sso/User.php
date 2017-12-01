<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	/**
	 * Description of User
	 *
	 * @author stretch
	 */
	class User extends N8_SingleSignOn {

		public function __construct() {
			parent::__construct();
			$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'dummy'));
		}

		/**
		 * register a user via SSO
		 */
		public function registerUser() {
			try {
				$this->authenticateSource();
				$post = $this->input->post();
				$post['password'] = crypt(microtime(), $this->vendor);
				$this->load->model('Admin_model','Admin',TRUE);
				$create_user = $this->Admin->createUser($post);
				if($create_user) {
					//success
					$this->response->status = HTTP_OK;
					$access_token = crypt(microtime(), $this->vendor);
					$this->cache->save($access_token, $this->input->post('email'), 1800);//cache token for 30 min.
					$this->response->iframe_url = base_url('/sso/User/getCookie/'.$access_token);
				}
			} catch(Exception $e) {
				log_message(LOG_LEVEL_ERROR, $e->getMessage());
			}

			echo json_encode($this->response);
		}

		/**
		 * set a cookie on the users browser for future login (requires an iframe from the client)
		 * @param string $access_token
		 */
		public function getCookie($access_token) {
			try {
				$data = $this->cache->get($access_token);
				if($this->input->post('email') == $data) {
					$cookie_val = crypt(microtime(), $this->vendor);
					$this->cache->save($cookie_val, $this->input->post('email'), 600);//cache token for 30 min.
					set_cookie('token', $cookie_val, (time()+600), base_url(), '/', null, true, true);
					set_cookie('user', $this->input->post('email'), (time()+600), base_url(), '/', null, true, true);

					$this->cache->delete($access_token);
				}
			} catch (Exception $e) {
				log_message(LOG_LEVEL_ERROR, $e->getMessage());
			}
		}

		/**
		 * log user in
		 */
		public function ssologin() {
			try {
				$email = $this->cache->get($this->input->cookie('token'));
				if($email == $this->input->cookie('user')) {
					//load user data
					$user = new Budget_DataModel_UserDM(['Email' => $email]);
					$login_array = ['Username' => $user->getUsername(), 'Password' => $user->getPassword()];
					$this->auth->process_login($login_array);
					$this->auth->redirect();
				} else {
					redirect(COMPANY_LOGOUT_REDIRECT);
				}
			} catch (Exception $e) {
				log_message(LOG_LEVEL_ERROR, $e->getMessage());
			}
		}
	}
