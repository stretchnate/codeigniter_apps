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

	/**
	* callback function to validate the recaptcha field
	*
	* @return boolean
	*/
   public function validate_captcha() {
	   $response = Form_Field_Recaptcha::validate($this->input->server("REMOTE_ADDR"),
									   $this->input->post("recaptcha_challenge_field"),
									   $this->input->post("recaptcha_response_field"));

	   if( !$response ) {
		   $this->form_validation->set_message( 'validate_captcha', 'The captcha wasn\'t entered correctly please try again');
	   }

	   return $response;
   }
}
