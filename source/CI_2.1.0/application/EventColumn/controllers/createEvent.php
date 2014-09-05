<?php

/**
 * this class is the controller class for adding and updating events as well as event locations and event details
 */
class createEvent extends N8_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->view('Event');
		$this->view = new EventVW();
//		$this->generateCategoriesNav();
	}

	/**
	 * load the event add page
	 *
	 * @return void
	 * @access public
	 * @since 1.0
	 */
	public function index() {
		try {
			$this->auth->restrict();

			if($this->input->post('event_submit')) {
				$this->addEvent();
			}

			$form_builder = new FormBuilder('', 'post', null, 'event_add_form',
											null, null, null, Form::FORM_ENCTYPE_MULTIPART);

			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, 'event_name', 'event_name', 'toggle_text', $this->getPostValue('event_name', 'title'));
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, 'start_date', 'start_date', 'toggle_text', $this->getPostValue('start_date', 'start date'));
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT, 'end_date', 'end_date', 'toggle_text', $this->getPostValue('end_date', 'end date'));

			$location_field = $form_builder->buildSimpleField(Form_Field::FIELD_TYPE_INPUT, 'event_details_locations[0][event_location_name]', 'event_details_locations[0][event_location_name]', 'toggle_text', $this->getPostValue('event_details_locations[0][event_location_name]', 'venue'));

			$error = form_error( 'event_details_locations[0][event_location_name]' );
			if(form_error( 'event_details_locations[0][lat_long]' )) {
				$error .= "<br />".form_error( 'event_details_locations[0][lat_long]' );
			}

			$form_builder->addFieldToForm($location_field);

			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_HIDDEN,
										'event_details_locations[0][lat_long]',
										'event_details_locations[0][lat_long]',
										null,
										$this->getPostValue('event_details_locations[0][lat_long]', ''));

			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT,
										'event_details_locations[0][event_address]',
										'event_details_locations[0][event_address]',
										'toggle_text',
										$this->getPostValue('event_details_locations[0][event_address]', 'address'));

			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT,
										'event_details_locations[0][event_city]',
										'event_details_locations[0][event_city]',
										'toggle_text',
										$this->getPostValue('event_details_locations[0][event_city]', 'city'));

			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT,
										'event_details_locations[0][event_state]',
										'event_details_locations[0][event_state]',
										'toggle_text',
										$this->getPostValue('event_details_locations[0][event_state]', 'state'));

			$zip_field = $form_builder->buildSimpleField(Form_Field::FIELD_TYPE_INPUT,
														'event_details_locations[0][event_zip]',
														'event_details_locations[0][event_zip]',
														'toggle_text',
														$this->getPostValue('event_details_locations[0][event_zip]', 'zip'));
			$zip_field->setMaxLength("5");
			$form_builder->addFieldToForm($zip_field);

			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_HIDDEN,
										'event_details_locations[0][event_country]',
										'event_details_locations[0][event_country]',
										null, 'USA');

			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_INPUT,
										'event_details_locations[0][event_cost]',
										'event_details_locations[0][event_cost]',
										'toggle_text',
										$this->getPostValue('event_details_locations[0][event_cost]', 'admission'));

			$smoke_field = $form_builder->buildSimpleField(Form_Field::FIELD_TYPE_CHECKBOX,
															'event_details_locations[0][smoking]',
															null, 'toggle_text', '');
            $smoke_field->setLabel("smoking");

			$form_builder->addFieldToForm($smoke_field);

			$food_field = $form_builder->buildSimpleField(Form_Field::FIELD_TYPE_SELECT,
															'event_details_locations[0][food]',
															'event_details_locations[0][food]',
															'toggle_text', '');
            $food_field->addOption("", "Food & drinks");
			$food_field->addOption("free", "Free");
			$food_field->addOption("on_sale", "On Sale");
			$food_field->addOption("no", "No");
            $food_field->setSelectedOption($this->input->post($food_field->getName()));

			$form_builder->addFieldToForm($food_field);

			$age_field = $form_builder->buildSimpleField(Form_Field::FIELD_TYPE_SELECT,
															'event_details_locations[0][age]',
															'event_details_locations[0][age]',
															'toggle_text', '');
			$age_field->addOption("", "Age Range*");
			$age_field->addOption("18_35", "18-35");
			$age_field->addOption("30_50", "30-50");
			$age_field->setSelectedOption($this->input->post($age_field->getName()));

			$form_builder->addFieldToForm($age_field);

			$description_field = $form_builder->buildSimpleField(Form_Field::FIELD_TYPE_TEXTAREA,
															'description', 'description',
															'toggle_text',
															$this->getPostValue('description' , 'description'));

			$description_field->setRows("3");
			$description_field->setCols("31");

			$form_builder->addFieldToForm($description_field);

			$error_array = array('class' => 'error', 'id' => null, 'content' => form_error('category'));
			$categories_list = new CategoriesList();
			$categories_list->fetchCategories();
			$categories_list->buildSelectObject("category", 'toggle_text', 'Event Type', $this->input->post('category'), $error_array);
			$categories_obj = $categories_list->getSelectObject();

			$form_builder->addFieldToForm($categories_obj);

			$file_field = $form_builder->buildSimpleField(Form_Field::FIELD_TYPE_FILE, 'userfile', 'userfile', null, '');

			$file_field->setLabel("event image");
			$file_field->setAccept(Form_Field_Input_File::ACCEPT_TYPE_IMAGE);

			$form_builder->addFieldToForm($file_field);

			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_HIDDEN, 'event_submit', null, null, 'event_submit' );
			$form_builder->addSimpleField(Form_Field::FIELD_TYPE_BUTTON, null, 'event_submit', 'ec_button', 'add event');

			$this->view->setErrors($this->getErrors());
			$this->view->setEventForm($form_builder->getForm());
			$this->view->setPageId("event_add");

			$this->view->renderView();
		} catch (Exception $e) {
			log_message('error', $e->getMessage(), false);
			show_error("there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500);
		}
	}

	/**
	 * add a new event
	 *
	 * @return void
	 * @access public
	 * @since 1.0
	 */
	public function addEvent() {
		try {
			if ($this->validate('add_event')) {
                $upload_data = $this->uploadFlyer();
				$post = & $this->input->post();
				$locations = $post['event_details_locations'];
				unset($post['event_details_locations']);

                $post['event_image'] = substr($upload_data['full_path'], 21);
				//add event owner to post array
				$post['event_owner'] = $this->session->userdata('user_id');

				$event_model = new EventModel();
				$event_model->saveEvent($post, $locations);

				if ($event_model->getErrors()) {
					$this->setErrors($event_model->getErrors());
				} else {
					redirect('/map/event_details/'.  EventMask::maskEventId($event_model->getEventDM()->getEventId()));
				}
			}
		} catch(Exception $e) {
			$this->setError($e->getMessage());
			$log = 'Exception Caught in '.$e->getFile().' on line '.$e->getLine().': '.$e->getMessage();
			$this->logMessage($log, N8_ERROR::ERROR);
		}
	}

    /**
     * uploads the event flyer
     *
     * @throws Exception
     * @return array
     */
	protected function uploadFlyer() {
        $file_upload = new FileUpload();
        $file_upload->initialize('event_image');
        return $file_upload->doUpload();
	}

}

?>
