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
	class SSO extends N8_Controller {
		public function __construct() {
			parent::__construct();
		}

		public function getToken() {
			$data = array('success' => true, 'token' => md5(time()));

			echo json_encode($data);
		}
	}
