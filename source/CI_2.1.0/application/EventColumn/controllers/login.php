<?php
	if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	require_once(APPPATH . 'third_party/phpass-0.3/PasswordHash.php');

	/**
	 * Description of login
	 *
	 * @author stretch
	 */
	class login extends N8_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->helper('form_validation');
			$this->load->view( 'UserLogin' );
			$this->view = new UserLoginVW();
			$this->view->setPageId('login');
		}

		public function index() {
			try {
				//build the login form
				$login_form = $this->buildLoginForm();

				//build the register form
				$register_form = $this->buildRegisterForm();

				$this->view->setErrors( $this->getErrors() );
				$this->view->setLoginForm( $login_form );
				$this->view->setRegisterForm( $register_form );

				$this->view->renderView();
			} catch( Exception $e ) {
				$message = "there was an error loading this page. Please try again.";
				Utilities::show500($message, $e);
			}
		}

		/**
		 * builds the login form
		 *
		 * @return \Form
		 * @access private
		 * @since 1.1
		 */
		private function buildLoginForm() {
			if($this->input->post('login_submit')) {
				$this->processLogin();
			}

			$form_builder = new FormBuilder("", 'post', null, 'login_form');
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, "login_username", "login_username", 'toggle_text', $this->getPostValue('login_username', 'Username'));
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, "login_password", "login_password", 'replace_type new_type_password toggle_text', 'Password');
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_SUBMIT, "login_submit", "login_submit", null, 'Login');

			return $form_builder->getForm();
		}

		/**
		 * builds the register form
		 *
		 * @return \Form
		 * @access private
		 * @since 1.1
		 */
		private function buildRegisterForm() {
			if($this->input->post('register_submit')) {
				$this->addUser();
			}

			$form_builder = new FormBuilder('', 'post', null, 'register_form');
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, 'username', 'username', 'toggle_text', $this->getPostValue('username', 'Username'));
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, 'email', 'email', 'toggle_text', $this->getPostValue('email', 'Email'));
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, 'confirm_email', 'confirm_email', 'toggle_text', $this->getPostValue('confirm_email', 'Confirm Email'));
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, 'password', 'password', 'replace_type new_type_password toggle_text', 'Password');
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, 'confirm_password', 'confirm_password', 'replace_type new_type_password toggle_text', 'Confirm Password');

			$zip_field = $form_builder->buildSimpleField(Form_Field::FIELD_TYPE_INPUT, 'zip', 'zip', 'toggle_text', $this->getPostValue('zip', 'Zip'));
			$zip_field->setMaxLength('5');
			$form_builder->addFieldToForm($zip_field);

			//build terms field
			$terms_field = Form::getNewField( Form_Field::FIELD_TYPE_CHECKBOX );
			$terms_field->setLabelContainerClass("float_right checkbox_label");
			$terms_field->setLabel( "Agree to Terms and Policies" );
			$terms_field->setValue( "agreed" );
			if( $this->input->post( $terms_field->getName() ) == 'agreed' ) {
				$terms_field->setChecked( true );
			}
			$terms_field->addErrorLabel( 'error', null, form_error( $terms_field->getName() ) );

			$form_builder->addFieldToForm($terms_field);

			//build recaptcha field
			$recaptcha_field = Form::getNewField( Form_Field::FIELD_TYPE_RECAPTCHA );
			$recaptcha_field->setContainerClass("recaptcha_container");
			$recaptcha_field->setLabel( "Please proove you're human" );
			$recaptcha_field->addErrorLabel('error', 'recaptcha_error', form_error('recaptcha_response_field'));

			$form_builder->addFieldToForm( $recaptcha_field );

			//add submit button to form
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_BUTTON, null, 'register_submit', null, 'Sign Up');

			return $form_builder->getForm();
		}

		/**
		 * process the user login
		 *
		 * @return void
		 * @since  1.0
		 */
		protected function processLogin() {
			if(!$this->auth->isSiteActive()) {
				redirect("/inactive/");
			}

			if($this->validate('login')) {
				$login = $this->auth->process_login($this->input->post('login_username'), $this->input->post('login_password'));
				if($login !== true) {
					$this->setError($login);
				} else {
					// Login successful, let's redirect.
					$this->auth->redirect();
				}
			}
		}

		/**
		 * main controller method for forgot password.
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function forgotPassword() {
			$this->load->view('forgotPassword');

			try {
				if($this->input->post('forgot_password_submit')) {
					$this->generatePassword();
				}

				$form_builder = new FormBuilder('login/forgotPassword', 'post', null, 'forgot_password_form');
				$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, 'email', 'email', 'toggle_text', $this->getPostValue('email', 'Email'));
				$form_builder->addSimpleField(Form_Field::FIELD_TYPE_BUTTON, 'forgot_password_submit', 'forgot_password_submit', null, 'Submit');

				//add the view
				$this->view = new forgotPasswordVW();
				$this->view->setPageId('login');
				$this->view->setErrors( $this->getErrors() );
				$this->view->setForgotPasswordForm( $form_builder->getForm() );
				$this->view->renderView();

			} catch(Exception $e) {
				$this->logError( $e->getMessage() );
				Utilities::show500('there was an error loading this page. Please try again', $e);
			}

		}

		/**
		 * Generates a random password
		 *
		 * @return void
		 * @since 1.0
		 */
		protected function generatePassword() {
			if($this->validate('forgot_password')) {
				try {
					$this->load->helper('password');
					$password      = passwordHelper::generatePassword();

					$phpass		   = new PasswordHash( Auth::PHPASS_ITERATIONS, Auth::PHPASS_PORTABLE_HASH );
					$password_hash = $phpass->HashPassword( $password );

					if($this->savePassword($this->input->post('email'), $password_hash) === true) {
						$this->sendPasswordEmail($this->input->post('email'), $password);
					} else {
						$this->setError('unable to generate new password');
					}
				} catch(Exception $e) {
					$this->setError($e->getMessage());
					$log = "Exception caught in ".$e->getFile()." on line ".$e->getLine().": ".$e->getMessage();
					$this->logMessage($log, N8_ERROR::ERROR);
				}
			}
		}

		/**
		 * saves the new password to the user profile
		 *
		 * @param string $email
		 * @param string $password_hash
		 * @return boolean
		 * @since 1.0
		 */
		private function savePassword($email, $password_hash) {
			$result = true;
			try {
				$user_profile_dm = new UserProfileDM();
				$user_profile_dm->setEmail($email);
				$user_profile_dm->loadProfileByEmail();

				if($user_profile_dm->getUsername()) {
					$user_profile_dm->setPassword($password_hash);
					$user_profile_dm->setTemporaryPassword(true);
					$user_profile_dm->save();
				}
			} catch(Exception $e) {
				$this->logMessage($e->getMessage(), N8_Error::ERROR);
				$result = false;
			}

			return $result;
		}

		/**
		 * sends an email to the user with their new password
		 *
		 * @param string $email
		 * @param string $raw_password
		 * @throws Exception
		 */
		private function sendPasswordEmail($email, $raw_password) {
			//load the CI email library
			$this->load->library('email');

			$this->email->from('webmaster@EventColumn.com');
			$this->email->to($email);
			$this->email->subject('password reset information');

			$message = "You recently requested a new password at EventColumn.com, your new password is ".$raw_password .
					" if you feel this password reset is in error or you did not request a password reset please contact us".
					"<br /><br />Thank You,<br />The EventColumn Staff";

			$this->email->message($message);

			if(!$this->email->send()) {
				throw new Exception("unable to send email to " . $email);
			}
		}

		/**
		 * logs out of a session
		 */
		public function logout() {
			$this->auth->logout();
		}

		/**
		 * Validates the user info and adds the user to the database
		 *
		 * @return void
		 * @since 1.0
		 */
		public function addUser() {
			require_once(APPPATH . 'third_party/phpass-0.3/PasswordHash.php');
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
				} else {
					//account created, log user in.
					$login = $this->auth->process_login($this->input->post('username'), $this->input->post('password'));
					if($login !== true) {
						$this->setError($login);
					} else {
						// Login successful, let's redirect.
						$this->auth->redirect();
					}
				}
			}
		}
	}

?>
