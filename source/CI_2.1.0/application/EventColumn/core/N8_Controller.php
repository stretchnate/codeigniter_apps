<?php

	if (!defined('BASEPATH'))
		exit('No direct script access allowed');

	class N8_Controller extends CI_Controller {

		protected $view;

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

	   /**
		* callback function to validate the password field
		*
		* @param  string $str
		* @return boolean
		*/
	   public function validate_password( $str ) {
		   $result = alpha_special( $str );

		   if( $result === 0 ) {
			   $this->form_validation->set_message( 'validate_password', 'Invalid characters found in the %s field. Allowed characters are a-zA-Z0-9_-!$@%*&^?|' );
		   }

		   return Utilities::getBoolean( $result );
	   }

	   /**
		* check to see if an email address already exists
		*
		* @param type $email
		*/
	   public function emailExists($email) {
			$result = false;
			try {
				$user_profile_dm = new UserProfileDM();
				$user_profile_dm->setEmail($email);
				$user_profile_dm->loadProfileByEmail();

				if($user_profile_dm->getUsername()) {
					$result = true;
				}
			} catch(Exception $e) {
				$this->logMessage($e->getMessage(), N8_Error::ERROR);
			}

			return $result;
	   }
	}
