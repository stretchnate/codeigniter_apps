<?php
	/**
	 * Description of userProfile
	 *
	 * @author stretch
	 */
	class user extends N8_Controller {

		private $cache_util;
        private $user_profile_dm;
        private $form;

		public function __construct() {
			parent::__construct();
			$this->load->view('UserProfile');
			$this->view = new UserProfileVW();
			$this->view->setPageId('user_profile');
		}

        public function index() {
            redirect('/user/profile');
        }

        /**
         * displays the events owned by the user
         *
         * @access public
         * @return void
         */
        public function events() {
            $this->auth->restrict();
            $this->load->view('UserEvents');
            $this->view = new UserEventsVW();
            $this->view->setPageId('map');
            $this->view->setMiniSearch(new Plugins_MiniSearch());

            $iterator = $this->getEventIterator();

            $this->view->setEventIterator($iterator);
            $this->view->setErrors($this->getErrors());
			$this->view->renderView();
        }

        /**
         * creates the EventIterator from the user id
         *
         * @access private
         * @return \EventIterator
         */
        private function getEventIterator() {
            try {
				return new EventIterator(
                        null, null, null, null, null, null, null, null,
                        $this->session->userdata('user_id')
                        );
			} catch(Exception $e) {
				$this->setMessage($e->getMessage());
			}
        }

        /**
         * build the user profile table
         *
         * @return void
         */
		public function profile() {
			$this->auth->restrict();

			try {
                $this->fetchUserProfileDM();
                $cache_key = $this->cacheUserProfile();

                $this->startForm();
				$this->form->addField($this->buildUserProfileField($cache_key));
                $this->form->addField($this->buildUsernameField());
				$this->form->addField($this->buildEmailField());
                $this->form->addField($this->buildCurrentEmailField());
				$this->form->addField($this->buildCurrentPasswordField());
                $this->form->addField($this->buildNewPasswordField());
                $this->form->addField($this->buildConfirmNewPasswordField());
                $this->form->addField($this->buildSubmitImage());

				$this->view->setErrors( $this->getErrors() );
				$this->view->setForm( $this->form );

				$this->view->renderView();
			} catch( Exception $e ) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}

        /**
         * build the user profile form submit image
         *
         * @return \Form_Field_Input_Image
         */
        private function buildSubmitImage() {
            $submit = new Form_Field_Input_Image();
            $submit->setId( "profile_submit" );
            $submit->setClass( 'form_input form_text' );
            $submit->setSrc( '/images/save_profile_btn.jpg' );
            $submit->setAlt( 'Save' );

            return $submit;
        }

        /**
         * build the confirm password field
         *
         * @return \Form_Field_Input_Password
         */
        private function buildConfirmNewPasswordField() {
            $field = new Form_Field_Input_Password();
            $field->setLabel( "Confirm New Password" );
            $field->setClass('form_input form_text');
            $field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

            return $field;
        }

        /**
         * build the new password field
         *
         * @return \Form_Field_Input_Password
         */
        private function buildNewPasswordField() {
            $field = new Form_Field_Input_Password();
            $field->setLabel( "New Password" );
            $field->setClass('form_input form_text');
            $field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

            return $field;
        }

        /**
         * build the current password field
         *
         * @return \Form_Field_Input_Password
         */
        private function buildCurrentPasswordField() {
            $field = new Form_Field_Input_Password();
            $field->setLabel( "Current Password" );
            $field->setClass('form_input form_text');
            $field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

            return $field;
        }

        /**
         * build the current email field
         * @return \Form_Field_Hidden
         */
        private function buildCurrentEmailField() {
            $field = new Form_Field_Hidden();
            $field->setName( "current_email" );
            $field->setId("current_email");
            $field->setClass('form_input form_text');
            $field->setValue( $this->user_profile_dm->getEmail() );

            return $field;
        }

        /**
         * build the email field
         *
         * @return \Form_Field_Input
         */
        private function buildEmailField() {
            $field = new Form_Field_Input();
            $field->setLabel( "Email" );
            $field->setClass('form_input form_text');
            $field->setValue( $this->user_profile_dm->getEmail() );
            $field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

            return $field;
        }

        /**
         * build the username field
         *
         * @return \Form_Field_Input
         */
        private function buildUsernameField() {
            $field = new Form_Field_Input();
            $field->setLabel( "Username" );
            $field->setClass('form_input form_text');
            $field->setValue( $this->user_profile_dm->getUsername() );
            $field->setReadonly(true);
            $field->setDisabled(true);
            $field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

            return $field;
        }

        /**
         * build the user profile field
         *
         * @param string $cache_key
         * @return \Form_Field_Hidden
         */
        private function buildUserProfileField($cache_key) {
            $field = new Form_Field_Hidden();
            $field->setName('user_profile');
            $field->setValue($cache_key);

            return $field;
        }

        /**
         * start the user profile form
         *
         * @return void
         */
        private function startForm() {
            $this->form = new Form();
            $this->form->setAction( "user/update" );
            $this->form->setId('user_profile_form');
        }

        /**
         * cache the user profile dm
         *
         * @return string
         */
        private function cacheUserProfile() {
            $this->cache_util = new CacheUtil();
            $cache_key = $this->cache_util->generateCacheKey('user_profile_');
            $this->cache_util->saveCache($cache_key, serialize($this->user_profile_dm));

            return $cache_key;
        }

        /**
         * fetch the user profile dm
         *
         * @return void;
         */
        private function fetchUserProfileDM() {
            $this->user_profile_dm = new UserProfileDM();
			$this->user_profile_dm->load($this->session->userdata('user_id'));
        }

        /**
         * update the users profile
         *
         * @return void
         */
		public function update() {
			$this->auth->restrict();
			if( $this->validate( 'update_profile' ) ) {
				if(!$this->cache_util) {
					$this->cache_util = new CacheUtil();
				}
				$this->user_profile_dm = unserialize($this->cache_util->fetchCache($this->input->post('user_profile')));
				$valid_password = $this->user_profile_dm->checkUserPassword($this->input->post('current_password'));
				if($valid_password !== true) {
					$this->setMessage("We were unable to process your change at this time, please try again later", N8_Error::ERROR);
					$this->logMessage("Invalid password attempt for ".$this->input->post('username'));
					$this->user_profile_dm->increaseLoginAttempts();

					$this->index();
				} else {
					//update values in the user profile.
					$this->updateProfile($this->user_profile_dm, $this->input->post(null, true));
					redirect('/user/profile');
				}
			} else {
				$this->index();
			}
		}

        /**
         * update the user profile in the database
         *
         * @param UserProfileDM $user_profile_dm
         * @param type $post
         */
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
