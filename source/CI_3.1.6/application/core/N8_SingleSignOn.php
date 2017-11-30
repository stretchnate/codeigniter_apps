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

		public function __construct($token) {
			parent::__construct();

			$response = new stdClass();
			$response->status = HTTP_BAD_REQUEST;

			try {
				$key = file_get_contents('/path/to/key');
				$res_prv = openssl_get_privatekey($key);

				openssl_private_decrypt($token, $decrypted, $res_prv);

				$decrypted_parts = explode('.', $decrypted);

				$where = ['name' => $decrypted_parts[0], 'token' => $decrypted_parts[1]];
				$client = new Budget_DataModel_ClientDM($where);

				if($client->getId()) {
					//success
					$response->status = HTTP_OK;
					$access_token = crypt(microtime(), $decrypted_parts[0]);
					$response->iframe_url = base_url('/sso/User/getCookie/'.$access_token);
				}
			} catch(Exception $e) {
				//log error and email
				$response->status = HTTP_INTERNAL_SERVER_ERROR;
			}

			echo json_encode($response);
//			$c = base64_decode($ciphertext);
//			$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
//			$iv = substr($c, 0, $ivlen);
//			$hmac = substr($c, $ivlen, $sha2len=32);
//			$ciphertext_raw = substr($c, $ivlen+$sha2len);
//			$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, true);
//
//			if(hash_equals($hmac, $calcmac)) {
//				$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
//				echo $original_plaintext."\n";
//			}
		}
	}
