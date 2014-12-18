<?php
	if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

	/**
	 * Description of content
	 *
	 * @author stretch
	 */
	class content extends N8_Controller {

		const VIEW_CONFIG_FILE   = '/var/www/source/CI_2.1.0/application/EventColumn/site_cfg/pageConfig.xml';
		const VIEW_CONFIG_SCHEMA = '/var/www/source/CI_2.1.0/application/EventColumn/site_cfg/pageConfig.xsd';


		public function __construct() {
			parent::__construct();
			$this->load->view('Content');
			$this->view = new ContentVW();
			$this->view->setMiniSearch(new Plugins_MiniSearch());
		}

		public function about() {
			$content = $this->getViewContent('about');

			$this->view->setContent($content);
			$this->view->setPageId('about');
			$this->view->renderView();
		}

		public function policies() {
			$content = $this->getViewContent('policies');

			$this->view->setContent($content);
			$this->view->setPageId('policies');
			$this->view->renderView();
		}

		public function contactUs() {
			$submit = Utilities::getBoolean($this->input->post('submit'));

			if($submit === true && $this->validate( 'contact_us' ) ) {
				$mail_sent = $this->sendEmail($this->input->post());
			}

			$this->view->setPageId('contact');

			$form = new Form();
			$form->setAction('');

			$field = Form::getNewField(Form_Field::FIELD_TYPE_TEXT);
			$field->setLabel( "Username" );
			$field->setName( "username" );
			$field->setValue( $this->input->post( 'username' ) );
			$field->addErrorLabel( 'error', null, form_error( 'username' ) );

			$form->addField( $field );

			$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
			$field->setLabel( "Email" );
			$field->setName( "email" );
			$field->setValue( $this->input->post( 'email' ) );
			$field->addErrorLabel( 'error', null, form_error( 'email' ) );

			$form->addField($field);

			$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
			$field->setLabel( "Subject" );
			$field->setName( "subject" );
			$field->setValue( $this->input->post( 'subject' ) );
			$field->addErrorLabel( 'error', null, form_error( 'subject' ) );

			$form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_TEXTAREA);
			$field->setLabel("Email Text");
			$field->setName("email_text");
			$field->setRows("3");
			$field->setCols("31");

			$form->addField($field);

			$field = Form::getNewField( Form_Field::FIELD_TYPE_RECAPTCHA );
			$field->setLabel( "Please proove you're human*" );
			$field->addErrorLabel('error', 'recaptcha_error', form_error('recaptcha_response_field'));

			$form->addField( $field );

			$field = Form::getNewField( Form_Field::FIELD_TYPE_SUBMIT );
			$field->setId( "submit" );
			$field->setName("submit");
			$field->setValue("submit");
			$field->setValue( "Change this to a button and add Javascript" );

			$form->addField( $field );

			if(isset($mail_sent) && $mail_sent === true) {
				$this->view->setSuccessMessages("Thank you for conatacting us.");
			} else {
				$this->view->setErrors( $this->getErrors() );
			}

			$this->view->setContent( $form );
			$this->view->renderView();
		}

		/**
		 * sends an email
		 *
		 * @param  array $data
		 * @return void
		 * @since  1.0
		 */
		protected function sendEmail(array $data) {
			$this->load->library('email');

			$this->email->from($data['email'], $data['username']);
			$this->email->to('stretchnate@gmail.com');
			$this->email->cc('leighton_irving@yahoo.com');

			$this->email->subject($data['subject']);
			$this->email->message($data['email_text']);

			$result = $this->email->send();
			if($result === false) {
				$this->addError("Email failed to send, please try again");
			}

			$this->setMessage($this->email->print_debugger(), N8_Error::DEBUG);

			return $result;
		}

		/**
		 * retreives the view content from the config file
		 *
		 * @param  string $view
		 * @return string
		 * @since  1.0
		 */
		protected function getViewContent($view) {
			$result     = null;
			$config_xml = new SimpleXMLElement( self::VIEW_CONFIG_FILE, null, true );

			if( Utilities::XMLIsValid( $config_xml, self::VIEW_CONFIG_SCHEMA ) ) {
				$view_config = $config_xml->xpath( 'Page[@id="'.$view.'"]/Body' );
				if(!empty($view_config)) {
					$result = (string)$view_config[0];
				}
			}

			return $result;
		}
	}

?>
