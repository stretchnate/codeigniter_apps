<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	require_once(APPPATH.'core/N8_SingleSignOn.php');
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
		 * LOG A USER IN VIA SSO
		 */
		public function userLogin() {
			if($this->authenticateSource()) {
				try {
					$user = new Budget_DataModel_UserDM(['email' => $this->input->post('email', true)]);
					if($user->getId()) {
						$this->response->status = HTTP_OK;
						$access_token = crypt(microtime(), $this->vendor);
						$this->cache->save($access_token, $this->input->post('email', true), 180);//cache token for 3 min.
						$this->response->iframe_url = base_url('/sso/User/getCookie/'.$access_token);

						if(!$user->getActive()) {
							$user->setActive(true);
							$user->save();
						}
					} else {
						$this->registerUser();
					}
				} catch(Exception $e) {
					$this->response->status = HTTP_INTERNAL_SERVER_ERROR;
					log_message(LOG_LEVEL_ERROR, $e->getMessage());
				}
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
					$this->cache->save($cookie_val, $this->input->post('email'), 180);//cache token for 3 min.
					header("Access-Control-Allow-Methods: POST");
					header("Access-Control-Allow-Origin: http://ciguide.local/");
					setcookie('token', $cookie_val, (time()+180), '/'.__CLASS__, base_url(), true, true);
					setcookie('user', $this->input->post('email'), (time()+180), '/'.__CLASS__, base_url(), true, true);
//					setcookie($access_token, 'yes', (time()+180), '/'.__CLASS__, base_url(), true, true);
					$this->response->status = HTTP_OK;

					$this->cache->delete($access_token);
				}
			} catch (Exception $e) {
				$this->response->status = HTTP_INTERNAL_SERVER_ERROR;
				log_message(LOG_LEVEL_ERROR, $e->getMessage());
			}

			echo json_encode($this->response);
		}

		/**
		 * check to see if an auth cookie has been set, this may not be needed as once the iframe loads (document is ready)
		 * the cookies should be set
		 */
		public function checkCookie() {
			if($this->authenticateSource() !== true) {
				die(json_encode($this->response));
			}

			try {
				$this->response->status = 0;
				if($this->input->cookie($this->input->post('access_token', true), true)) {
					$this->response->status = HTTP_OK;
				}
			} catch (Exception $e) {
				$this->response->status = HTTP_INTERNAL_SERVER_ERROR;
				log_message(LOG_LEVEL_ERROR, $e->getMessage());
			}

			echo json_encode($this->response);
		}

		/**
		 * log user in
		 */
		public function ssoLogin() {
			try {
				$email = $this->cache->get($this->input->cookie('token'));
				if($email && $email == $this->input->cookie('user')) {
					//load user data
					$user = new Budget_DataModel_UserDM(['Email' => $email]);
					if(!$user->getId()) {
						redirect(COMPANY_LOGOUT_REDIRECT);
					}
					$login_array = ['Username' => $user->getUsername(), 'Password' => $user->getPassword()];

					$this->auth->process_login($login_array, true);
					$this->auth->redirect();
				} else {
					redirect(COMPANY_LOGOUT_REDIRECT);
				}
			} catch (Exception $e) {
				$this->response->status = HTTP_INTERNAL_SERVER_ERROR;
				log_message(LOG_LEVEL_ERROR, $e->getMessage());
			}
		}

		/**
		 * register a user via SSO
		 */
		private function registerUser() {
			try {
				$post = $this->input->post(null, true);
				$post['password'] = microtime();
				$this->load->model('Admin_model','Admin',TRUE);
				$create_user = $this->Admin->createUser($post);
				if($create_user) {
					//success
					$this->response->status = HTTP_OK;
					$access_token = crypt(microtime(), $this->vendor);
					$this->cache->save($access_token, $this->input->post('email', true), 300);//cache token for 5 min.
					$this->response->iframe_url = base_url('/sso/User/getCookie/'.$access_token);
				}
			} catch(Exception $e) {
				if($e->getCode() == EXCEPTION_CODE_VALIDATION) {
					$this->response->message = $e->getMessage();
				}
				$this->response->status = HTTP_INTERNAL_SERVER_ERROR;
				log_message(LOG_LEVEL_ERROR, $e->getMessage());
			}
		}
	}
