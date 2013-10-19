<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class N8_Controller extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	/**
	 * form validation method
	 *
	 * @param mixed $rules
	 * @return type
	 */
	function validate($rules) {
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		if (is_array($rules)) {
			$this->form_validation->set_rules($rules);
			$result = $this->form_validation->run();
		} else {
			//read rules from config/form_validation.php
			$result = $this->form_validation->run($rules);
		}

		return $result;
	}

}
