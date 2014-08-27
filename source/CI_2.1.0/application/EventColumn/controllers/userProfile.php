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
			$this->load->view('UserProfile');
			$this->view = new UserProfileVW();
			$this->view->setPageId('user_profile');
		}

		public function index() {
			$this->auth->restrict();

			try {
				$user_profile_dm = new UserProfileDM();
				$user_profile_dm->load($this->session->userdata('user_id'));

				$this->cache_util = new CacheUtil();
				$cache_key = $this->cache_util->generateCacheKey('user_profile_');
				$this->cache_util->saveCache($cache_key, serialize($user_profile_dm));

				$form = new Form();
				$form->setAction( "userProfile/update" );
				$form->setId('user_profile_form');

				$field = Form::getNewField(Form_Field::FIELD_TYPE_HIDDEN);
				$field->setName('user_profile');
				$field->setValue($cache_key);

				$form->addField($field);

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Username" );
                $field->setClass('form_input form_text');
				$field->setValue( $user_profile_dm->getUsername() );
				$field->setReadonly(true);
				$field->setDisabled(true);
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Email" );
                $field->setClass('form_input form_text');
				$field->setValue( $user_profile_dm->getEmail() );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_HIDDEN );
				$field->setName( "current_email" );
				$field->setId("current_email");
                $field->setClass('form_input form_text');
				$field->setValue( $user_profile_dm->getEmail() );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setLabel( "Current Password" );
                $field->setClass('form_input form_text');
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setLabel( "New Password" );
                $field->setClass('form_input form_text');
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setLabel( "Confirm New Password" );
                $field->setClass('form_input form_text');
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$form->addField( $field );

				$submit = Form::getNewField( Form_Field::FIELD_TYPE_IMAGE );
				$submit->setId( "profile_submit" );
                $submit->setClass( 'form_input form_text' );
                $submit->setSrc( '/images/save_profile_btn.jpg' );
                $submit->setAlt( 'Save' );

				$form->addField( $submit );

				$this->view->setErrors( $this->getErrors() );
				$this->view->setForm( $form );

				$this->view->renderView();
			} catch( Exception $e ) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}

		public function update() {
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
				} else {
					//update values in the user profile.
					$this->updateProfile($user_profile_dm, $this->input->post(null, true));
					redirect('/userProfile');
				}
			} else {
				$this->index();
			}
		}


		private function updateProfile(UserProfileDM $user_profile_dm, $post) {
			$save = false;

			if(isset($post['email']) && $post['email'] != $user_profile_dm->getEmail()) {
				$user_profile_dm->setEmail($post['email']);
				$save = true;
			}

			if(isset($post['new_password'])) {
				//@todo need to hash this
//				$user_profile_dm->setPassword($post['password']);
				$save = true;
			}

			if(isset($post['zip'])) {
				$user_profile_dm->setZip($post['zip']);
				$save = true;
			}

			if($save === true) {
				$user_profile_dm->save();
				$this->cache_util->saveCache($post['user_profile'], serialize($user_profile_dm));
			}
		}
	}

?>
