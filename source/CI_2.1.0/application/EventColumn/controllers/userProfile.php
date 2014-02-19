<?php
	/**
	 * Description of userProfile
	 *
	 * @author stretch
	 */
	class userProfile extends N8_Controller {

		private $cache_util;

		public function __construct() {
			parent::__construct();
		}

		public function index() {
			$this->auth->restrict();

			try {
				$this->load->view('UserProfile');
				$this->view = new UserProfileVW();

				$user_profile_dm = new UserProfileDM();
				$user_profile_dm->load($this->session->get_userdata('user_id'));

				$this->cache_util = new CacheUtil();
				$cache_key = $this->cache_util->generateCacheKey('user_profile_');
				$this->cache_util->saveCache($cache_key, serialize($user_profile_dm));

				$form = new Form();
				$form->setAction( "userProfile/updateProfile" );
				$form->setId('user_profile_form');

				$field = Form::getNewField(Form_Field::FIELD_TYPE_HIDDEN);
				$field->setName('user_id');
				$field->setValue($this->session_get_userdata('user_id'));

				$field = Form::getNewField(Form_Field::FIELD_TYPE_HIDDEN);
				$field->setName('user_profile');
				$field->setValue($cache_key);

				$form->addField($field);

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Username" );
				$field->setValue( $user_profile_dm->getUsername() );
				$field->setReadonly(true);
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Email" );
				$field->setValue( $user_profile_dm->getEmail() );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setLabel( "Current Password" );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setLabel( "New Password" );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setLabel( "Confirm New Password" );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Zip" );
				$field->setMaxLength( "5" );
				$field->setValue( $user_profile_dm->getZip() );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_BUTTON );
				$field->setId( "profile_submit" );
				$field->setContent( "Submit" );

				$form->addField( $field );

				$this->view->setErrors( $this->getErrors() );
				$this->view->setForm( $form );

				$this->view->renderView();
			} catch( Exception $e ) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}

		public function updateProfile() {
			$this->auth->restrict();
			if( $this->validate( 'update_profile' ) ) {
				if(!$this->cache_util) {
					$this->cache_util = new CacheUtil();
				}
				$user_profile_dm = unserialize($this->cache_util->fetchCache($this->input->post('user_profile')));
				$valid_password = $user_profile_dm->checkUserPassword($this->input->post('current_password'));
				if($valid_password !== true) {
					$this->setMessage("We were unable to process your change at this time, please try again later", N8_Error::ERROR);
					$this->logMessage("Invalid password attempt for ".$this->input->post('username'));
					$user_profile_dm->increaseLoginAttempts();

					$this->index();
				}
			} else {
				$this->index();
			}
		}
	}

?>
