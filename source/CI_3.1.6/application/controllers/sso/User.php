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

		public function getToken() {
			$data = array('success' => true, 'token' => md5(time()));

			echo json_encode($data);
		}

		public function getCookie($access_token) {

		}
	}
