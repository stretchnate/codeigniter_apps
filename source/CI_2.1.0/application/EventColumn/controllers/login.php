<?php
	if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

	/**
	 * Description of login
	 *
	 * @author stretch
	 */
	class login extends N8_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->helper('form_validation');
		}

		public function index() {
			$this->load->view( 'UserLogin' );
			try {
				$login_form = new Form();
				$login_form->setAction( "login/execute" );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setContainerClass( "login-form-field" );
				$field->setLabel( "Username" );
				$field->setLabelContainerClass( "login-form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "username" );
				$field->setName( "username" );
				$field->setValue( $this->input->post( 'username' ) );
				$field->addErrorLabel( 'error', null, form_error( 'username' ) );

				$login_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setContainerClass( "login-form-field" );
				$field->setLabel( "Password" );
				$field->setLabelContainerClass( "login-form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "password" );
				$field->setName( "password" );
				$field->addErrorLabel( 'error', null, form_error( 'password' ) );

				$login_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_BUTTON );
				$field->setContainerClass( "login-form-field" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "login-submit" );
				$field->setContent( "Login" );

				$login_form->addField( $field );

				$view = new UserLoginVW();
				$view->setPageId('login');
				$view->setErrors( $this->getErrors() );
				$view->setLoginForm( $login_form );
				$view->showMainNav(false);

				$view->renderView();
			} catch( Exception $e ) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}

		public function execute() {
			if(!$this->auth->isSiteActive()) {
				redirect("/inactive/");
			}

			$login = $this->auth->process_login($this->input->post('username'), $this->input->post('password'));
			if($login !== true) {
				$this->setError($login);
				$this->index();
			} else {
				// Login successful, let's redirect.
				$this->auth->redirect();
			}
		}

		public function logout() {
			$this->auth->logout();
		}
	}

?>
