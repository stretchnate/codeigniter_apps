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

		protected function authenticateSource() {
			try {
				$key = file_get_contents('/path/to/key');
				$res_prv = openssl_get_privatekey($key);

				openssl_private_decrypt($this->input->post('token'), $decrypted, $res_prv);

				$decrypted_parts = explode('.', $decrypted);

				$this->vendor = $decrypted_parts[0];

				$where = ['name' => $this->vendor, 'token' => $decrypted_parts[1]];
				$client = new Budget_DataModel_ClientDM($where);

				if(!$client->getId()) {
					echo json_encode($this->response);
					return false;
				}
			} catch(Exception $e) {
				//log error and email
				log_message(LOG_LEVEL_ERROR, $e->getMessage());
				$this->response->status = HTTP_INTERNAL_SERVER_ERROR;
				echo json_encode($this->response);
				return false;
			}
		}
	}
