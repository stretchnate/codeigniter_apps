<?php
	if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

	require_once(APPPATH . 'third_party/phpass-0.3/PasswordHash.php');

	/**
	 * Description of register
	 *
	 * @deprecated since version 1.1
	 * @author stretch
	 */
	class register extends N8_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->helper( 'form_validation' );
			$this->load->view( 'Register' );
			$this->view = new RegisterVW();
			$this->view->setPageId('register');
			$this->generateCategoriesNav();
		}

		public function index() {
			try {
				$register_form = new Form();
				$register_form->setAction( "register/addUser" );
				$register_form->setId('register_form');

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Username*" );
				$field->setValue( $this->input->post( $field->getName() ) );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Email*" );
				$field->setValue( $this->input->post( $field->getName() ) );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Confirm Email*" );
				$field->setValue( $this->input->post( $field->getName() ) );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setLabel( "Password*" );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setLabel( "Confirm Password*" );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Zip*" );
				$field->setMaxLength( "5" );
				$field->setValue( $this->input->post( $field->getName() ) );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_CHECKBOX );
				$field->setLabel( "Agree to Terms and Policies*" );
				$field->setValue( "agreed" );
				if( $this->input->post( $field->getName() ) == 'agreed' ) {
					$field->setChecked( true );
				}
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_RECAPTCHA );
				$field->setLabel( "Please proove you're human*" );
				$field->addErrorLabel('error', 'recaptcha_error', form_error('recaptcha_response_field'));

				$register_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_BUTTON );
				$field->setId( "register_submit" );
				$field->setContent( "Submit" );

				$register_form->addField( $field );

				$this->view->setErrors( $this->getErrors() );
				$this->view->setRegisterForm( $register_form );

				$this->view->renderView();
			} catch( Exception $e ) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}
	}

?>
