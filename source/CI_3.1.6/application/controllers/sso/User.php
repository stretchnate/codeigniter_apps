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
			$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
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
						$this->response->url = base_url('/sso/User/getCookie/');
						$this->response->access_token = $access_token;

						if(!$user->getActive()) {
							$user->setActive(true);
							$user->save();
						}
					} else {
						$this->registerUser($this->input->post(null, true));
					}
				} catch(Exception $e) {
					$this->response->status = HTTP_INTERNAL_SERVER_ERROR;
					log_message(LOG_LEVEL_ERROR, $e->getMessage());
				}
			}

			echo json_encode($this->response);
		}

		/**
		 * set a cookie on the users browser for future login
		 * @param string $access_token
		 */
		public function getCookie() {
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
			header("Access-Control-Allow-Credentials: true");
			header("Access-Control-Allow-Origin: https://whyibudget.com");
			header("Access-Control-Allow-Origin: https://courses.whyibudget.com");
			header("Access-Control-Allow-Headers: Content-Type, *");
			
			try {
				$access_token = $this->input->post('access_token');
				$data = $this->cache->get($access_token);
				if($this->input->post('email') == $data) {
					$cookie_val = crypt(microtime(), $this->vendor);
					$this->cache->save($cookie_val, $this->input->post('email'), 180);//cache token for 3 min.
					
					setcookie('token', base64_encode($cookie_val), (time()+60), '/', 'money.stretchnate.com', false, true);
					setcookie('useremail', $this->input->post('email'), (time()+60), '/', 'money.stretchnate.com', false, true);

					$this->response->status = HTTP_OK;
				} else {
				    $this->response->message = "Unable to login to budget using email ".$this->input->post('email');
				}
				$this->cache->delete($access_token);
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
                $token = base64_decode($this->input->cookie('token'));
                $email = $this->cache->get($token);
                $this->cache->delete($token);
                if($email && $email == $this->input->cookie('useremail')) {
                    //load user data
                    $user = new Budget_DataModel_UserDM(['Email' => $email]);
                    if(!$user->getId()) {
                        redirect(COMPANY_LOGOUT_REDIRECT);
                    }
                    $login_array = [$user->getUsername(), $user->getPassword()];

                    $this->auth->process_login($login_array, true);
                } else {
                    log_message(LOG_LEVEL_ERROR, "Email doesn't match");
                    redirect(COMPANY_LOGOUT_REDIRECT);
                }
            } catch (Exception $e) {
                $this->response->status = HTTP_INTERNAL_SERVER_ERROR;
                $this->response->message = 'There was a problem authorizing you with the budget.';
                log_message(LOG_LEVEL_ERROR, $e->getMessage());
                exit(json_encode($this->response));
            }

            $this->auth->redirect();
        }

		/**
		 * register a user via SSO
		 */
		private function registerUser($post) {
			try {
				$post['username'] = $this->input->post('email');
				$post['password'] = microtime();
				$this->load->model('Admin_model','Admin',TRUE);
				$create_user = $this->Admin->createUser($post);
				if($create_user) {
					//success
					$this->response->status = HTTP_OK;
					$access_token = crypt(microtime(), $this->vendor);
					$this->cache->save($access_token, $this->input->post('email', true), 300);//cache token for 5 min.
					$this->response->url = base_url('/sso/User/getCookie/');
					$this->response->access_token = $access_token;
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
