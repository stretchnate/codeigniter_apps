<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	/**
	 * Description of SSO
	 *
	 * @author stretch
	 */
	abstract class N8_SingleSignOn extends N8_Controller {

		protected $vendor;

		protected $response;

		public function __construct() {
			parent::__construct();

			$this->response = new stdClass();
			$this->response->status = HTTP_BAD_REQUEST;
		}

		/**
		 * authenticate the source of the request
		 *
		 * @return boolean
		 */
		protected function authenticateSource() {
			$result = false;
			try {
				$key = file_get_contents('/opt/bitnami/apache2/htdocs/whyibudget.com/.secure/wib_private.pem');
				$res_prv = openssl_get_privatekey($key, 'Quantum1');

				if(!openssl_private_decrypt(base64_decode($this->input->post('token')), $decrypted, $res_prv)) {
					$this->response->status = HTTP_INTERNAL_SERVER_ERROR;
					$this->response->message = "Unable to verify sender.";
					$this->response->data = json_encode($this->input->post());
				} else {
					$decrypted_parts = explode('.', base64_decode($decrypted));

					$this->vendor = $decrypted_parts[0];

					$where = ['name' => $this->vendor];
					$client = new Budget_DataModel_ClientDM($where);

					$verified = password_verify($decrypted_parts[1], $client->getToken());
					$result = ($client->getId() && $verified);
				}
			} catch(Exception $e) {
				//log error and email
				log_message(LOG_LEVEL_ERROR, $e->getMessage());
				$this->response->status = HTTP_INTERNAL_SERVER_ERROR;
				$this->response->message = $e->getMessage();
				$this->response->data = json_encode($this->input->post());
			}

			return $result;
		}
	}
