<?php
	if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

	require_once(APPPATH . 'third_party/phpass-0.3/PasswordHash.php');

	/**
	 * Description of register
	 *
	 * @author stretch
	 */
	class register extends N8_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->helper( 'form_validation' );
		}

		public function index() {
			$this->load->view( 'Register' );
			try {
				$register_form = new Form();
				$register_form->setAction( "register/addUser" );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setContainerClass( "register-form-field" );
				$field->setLabel( "Username*" );
				$field->setLabelContainerClass( "register-form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "username" );
				$field->setName( "username" );
				$field->setValue( $this->input->post( 'username' ) );
				$field->addErrorLabel( 'error', null, form_error( 'username' ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setContainerClass( "register-form-field" );
				$field->setLabel( "Email*" );
				$field->setLabelContainerClass( "register-form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "email" );
				$field->setName( "email" );
				$field->setValue( $this->input->post( 'email' ) );
				$field->addErrorLabel( 'error', null, form_error( 'email' ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setContainerClass( "register-form-field" );
				$field->setLabel( "Confirm Email*" );
				$field->setLabelContainerClass( "register-form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "confirm-email" );
				$field->setName( "confirm_email" );
				$field->setValue( $this->input->post( 'confirm_email' ) );
				$field->addErrorLabel( 'error', null, form_error( 'confirm_email' ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setContainerClass( "register-form-field" );
				$field->setLabel( "Password*" );
				$field->setLabelContainerClass( "register-form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "password" );
				$field->setName( "password" );
				$field->addErrorLabel( 'error', null, form_error( 'password' ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setContainerClass( "register-form-field" );
				$field->setLabel( "Confirm Password*" );
				$field->setLabelContainerClass( "register-form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "password-retype" );
				$field->setName( "password_retype" );
				$field->addErrorLabel( 'error', null, form_error( 'password_retype' ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setContainerClass( "register-form-field" );
				$field->setLabel( "Zip*" );
				$field->setLabelContainerClass( "register-form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "zip" );
				$field->setName( "zip" );
				$field->setMaxLength( "5" );
				$field->setValue( $this->input->post( 'zip' ) );
				$field->addErrorLabel( 'error', null, form_error( 'zip' ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_CHECKBOX );
				$field->setContainerClass( "register-form-field" );
				$field->setLabel( "Agree to Terms and Policies*" );
				$field->setLabelContainerClass( "register-form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "agree-to-terms" );
				$field->setName( "agree_to_terms" );
				$field->setValue( "agreed" );
				if( $this->input->post( 'agree_to_terms' ) == 'agreed' ) {
					$field->setChecked( true );
				}
				$field->addErrorLabel( 'error', null, form_error( 'agree_to_terms' ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_RECAPTCHA );
				$field->setContainerClass( "register-form-field" );
				$field->setLabel( "Please proove you're human*" );
				$field->setLabelContainerClass( "register-form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->addErrorLabel('error', 'recaptcha_error', form_error('recaptcha_response_field'));

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_SUBMIT );
				$field->setContainerClass( "register-form-field" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "register-submit" );
				$field->setValue( "Change this to a button and add Javascript" );

				$register_form->addField( $field );

				$view = new RegisterVW();
				$view->setErrors( $this->getErrors() );
				$view->setRegisterForm( $register_form );

				$view->renderView();
			} catch( Exception $e ) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}

		/**
		 * Validates the user info and adds the user to the database
		 *
		 * @return void
		 * @since 1.0
		 */
		public function addUser() {
			if( $this->validate( 'add_user' ) ) {
				//add some validation before hashing the password. 32 chars max on pw.
				$phpass		 = new PasswordHash( Auth::PHPASS_ITERATIONS, Auth::PHPASS_PORTABLE_HASH );
				$password	 = $phpass->HashPassword( $this->input->post( 'password' ) );

				$user_profile_dm = new UserProfileDM();
				$user_profile_dm->setUsername( $this->input->post( 'username' ) );
				$user_profile_dm->setEmail( $this->input->post( 'email' ) );
				$user_profile_dm->setPassword( $password );
				$user_profile_dm->setZip( $this->input->post( 'zip' ) );
				$user_profile_dm->setAgreeToTerms(true);

				if( !$user_profile_dm->save() ) {
					$message = 'Unable to save user [' . $this->input->post( 'username' ) . ']';

					$this->addError( $message );

					$message .= " with email [" . $this->input->post( 'email' ) . "]";
					$message .= " and zip [" . $this->input->post( 'zip' ) . "]";

					$this->logMessage( $message, N8_Error::ERROR );

					$this->index();
				} else {
					//account created, log user in.
					$login = $this->auth->process_login($this->input->post('username'), $this->input->post('password'));
					if($login !== true) {
						$this->setError($login);
						$this->index();//@todo do something better than this.
					} else {
						// Login successful, let's redirect.
						$this->auth->redirect();
					}
				}
			} else {
				$this->index();
			}
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

	}

?>
