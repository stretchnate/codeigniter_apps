<?php
	if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

	class N8_Controller extends CI_Controller {

		protected $view;

		function __construct() {
			parent::__construct();

			if( ! $this->session->userdata( 'location' ) && Utilities::isCLI() !== true) {
				$this->getLocation();
			}
		}

		/**
		 * form validation method
		 *
		 * @param mixed $rules
		 * @return type
		 */
		function validate( $rules ) {
			$this->load->helper( array( 'form', 'url' ) );
			$this->load->library( 'form_validation' );

			if( is_array( $rules ) ) {
				$this->form_validation->set_rules( $rules );
				$result = $this->form_validation->run();
			} else {
				//read rules from config/form_validation.php
				$result = $this->form_validation->run( $rules );
			}

			return $result;
		}

		/**
		 * callback function to validate the recaptcha field
		 *
		 * @return boolean
		 */
		public function validate_captcha() {
			$response = Form_Field_Recaptcha::validate( $this->input->server( "REMOTE_ADDR" ), $this->input->post( "recaptcha_challenge_field" ), $this->input->post( "recaptcha_response_field" ) );

			if( ! $response ) {
				$this->form_validation->set_message( 'validate_captcha', 'The captcha wasn\'t entered correctly please try again' );
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
			$result = true;

			if(!is_null($str) && $str != '') {
				$this->load->helper('form_validation');
				$result = alpha_special( $str );

				if( $result === 0 ) {
					$this->form_validation->set_message( 'validate_password', 'Invalid characters found in the %s field. Allowed characters are a-zA-Z0-9_-!$@%*&^?|' );
				}
			}

			return Utilities::getBoolean( $result );
		}

		/**
		 * check to see if an email address already exists
		 *
		 * @param type $email
		 */
		public function emailExists( $email ) {
			$result = false;
			try {
				$user_profile_dm = new UserProfileDM();
				$user_profile_dm->setEmail( $email );
				$user_profile_dm->loadProfileByEmail();

				if( $user_profile_dm->getUsername() ) {
					$result = true;
				}
			} catch( Exception $e ) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
			}

			return $result;
		}

		/**
		 * generates the categories list of links
		 *
		 * @return void
		 * @since  1.0
		 */
		protected function generateCategoriesNav() {
			$categories_list = new CategoriesList();
			$categories_list->setHrefBase( '/map/bycategory' );

			$location_array = $this->session->userdata( 'location' );
			if( ! empty( $location_array ) ) {
				try {
					$categories_list->fetchCategoriesByLocation( $location_array['city'], $location_array['state'], $location_array['zip'] );
				} catch(UnexpectedValueException $e) {
					$this->logMessage($e->getMessage());
					$categories_list->fetchCategories();
				}
			} else {
				$categories_list->fetchCategories();
			}

			$categories_list->buildUL( true );

			$this->view->setCategoriesNav( $categories_list );
		}

		/**
		 * gets the users location based on ip address from http://ipinfo.io/developers
		 *
		 * @return void
		 * @since  1.0
		 */
		private function getLocation() {
			$ip		 = $this->input->server( 'REMOTE_ADDR' );
			$details = json_decode( file_get_contents( "http://ipinfo.io/{$ip}/json" ) );

			$location = array('city' => '', 'state' => '', 'zip' => '');
			if((isset($details->city) && isset($details->region))) {
				$location['city']  =  $details->city;
				$location['state'] = $details->region;

				if(isset($details->postal)) {
					$location['zip'] = $details->postal;
				}
			} else {
				$location = array('city' => 'Orlando', 'state' => 'Florida', 'zip' => '32801');
			}

			$this->session->set_userdata('location',$location);
		}

		/**
		 * builds a very general field and returns it.
		 *
		 * @todo  this method needs a lot of work. Needs validation on the arguments
		 *        it also needs to be able to add more than a name/id and default label.
		 *
		 * @param string $type
		 * @param string $name_id
		 * @param string $default_label
		 * @return Form_Field_*
		 */
		protected function buildField($type, $name_id, $default_label) {
			$field = Form::getNewField($type);
			$field->setClass('toggle_text');
			$field->setName($name_id)->setId($name_id);
			$name_value = ($this->input->post($field->getName()) != '') ?
									$this->input->post($field->getName()) :
									$default_label;

			$field->setValue($name_value);
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			return $field;
		}

	}

