<?php

/**
 * this class is the controller class for adding and updating events as well as event locations and event details
 */
class Event extends N8_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * load the event add page
	 *
	 * @return void
	 * @access public
	 * @since 1.0
	 */
	public function index() {
		$this->auth->restrict();
		$this->load->view('Event');

		try {
			$event_form = new Form();
			$event_form->setAction("event/addEvent");
			$event_form->setEnctype(Form::FORM_ENCTYPE_MULTIPART);
			$event_form->setId("event_add_form");

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Event Name*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-name");
			$field->setName("event_name");
			$field->setValue($this->input->post('event_name'));
			$field->addErrorLabel( 'error', null, form_error( 'event_name' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Start Date*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-start");
			$field->setName("event_start_datetime");
			$field->setValue($this->input->post('event_start_datetime'));
			$field->addErrorLabel( 'error', null, form_error( 'event_start_datetime' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("End Date*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-end");
			$field->setName("event_end_datetime");
			$field->setValue($this->input->post('event_end_datetime'));
			$field->addErrorLabel( 'error', null, form_error( 'event_end_datetime' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Location Name*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-location-name");
			$field->setName("event_details_locations[0][event_location_name]");
			$field->setValue($this->input->post('event_details_locations[0][event_location_name]'));
			$field->addErrorLabel( 'error', null, form_error( 'event_details_locations[0][event_location_name]' ) );

			$error = form_error( 'event_details_locations[0][event_location_name]' );
			if(form_error( 'event_details_locations[0][lat_long]' )) {
				$error .= "<br />".form_error( 'event_details_locations[0][lat_long]' );
			}

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_HIDDEN);
			$field->setName("event_details_locations[0][lat_long]");
			$field->setId("event_details_locations[0][lat_long]");
			$field->setValue($this->input->post('event_details_locations[0][lat_long]'));

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Address*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-address");
			$field->setName("event_details_locations[0][event_address]");
			$field->setValue($this->input->post('event_details_locations[0][event_address]'));
			$field->addErrorLabel( 'error', null, form_error( 'event_details_locations[0][event_address]' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("City*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-city");
			$field->setName("event_details_locations[0][event_city]");
			$field->setValue($this->input->post('event_details_locations[0][event_city]'));
			$field->addErrorLabel( 'error', null, form_error( 'event_details_locations[0][event_city]' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("State*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-state");
			$field->setName("event_details_locations[0][event_state]");
			$field->setValue($this->input->post('event_details_locations[0][event_state]'));
			$field->addErrorLabel( 'error', null, form_error( 'event_details_locations[0][event_state]' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Zip*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-zip");
			$field->setName("event_details_locations[0][event_zip]");
			$field->setMaxLength("5");
			$field->setValue($this->input->post('event_details_locations[0][event_zip]'));
			$field->addErrorLabel( 'error', null, form_error( 'event_details_locations[0][event_zip]' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Country*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-country");
			$field->setName("event_details_locations[0][event_country]");

			$country = ($this->input->post('event_details_locations[0][event_country]')) ? $this->input->post('event_details_locations[0][event_country]') : "USA";
			$field->setValue($country);
			$field->addErrorLabel( 'error', null, form_error( 'event_details_locations[0][event_country]' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Price (optional)");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-cost");
			$field->setName("event_details_locations[0][event_cost]");
			$field->setValue($this->input->post('event_details_locations[0][event_cost]'));

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_CHECKBOX);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Smoking");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-location-smoking");
			$field->setName("event_details_locations[0][smoking]");
			$field->setValue($this->input->post('event_details_locations[0][smoking]'));

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_CHECKBOX);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Food Available");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-location-food");
			$field->setName("event_details_locations[0][food]");
			$field->setValue($this->input->post('event_details_locations[0][food]'));

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_SELECT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Age Range*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-location-age");
			$field->setName("event_details_locations[0][age]");
			$field->addOption("", "");
			$field->addOption("18_35", "18-35");
			$field->addOption("30_50", "30-50");
			$field->setSelectedOption($this->input->post('event_details_locations[0][age]'));
			$field->addErrorLabel( 'error', null, form_error( 'event_details_locations[0][age]' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_TEXTAREA);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Description*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-description");
			$field->setName("event_description");
			$field->setRows("3");
			$field->setCols("31");
			$field->setValue($this->input->post('event_description'));
			$field->addErrorLabel( 'error', null, form_error( 'event_description' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_SELECT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Category*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-category");
			$field->setName("event_category");
			$field->addOption("", "");
			$field->addOption("1", "Church Events");
			$field->addOption("2", "Festivals");
			$field->setSelectedOption($this->input->post('event_category'));
			$field->addErrorLabel( 'error', null, form_error( 'event_category' ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_FILE);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Event Image");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-image");
			$field->setName("event_image");
			$field->setAccept(Form_Field_Input_File::ACCEPT_TYPE_IMAGE);
			$field->setValue($this->input->post('event_image'));

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_BUTTON);
			$field->setContainerClass("event-form-field");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-submit");
			$field->setContent("Add Event");

			$event_form->addField($field);

			$view = new EventVW();
			$view->setErrors($this->getErrors());
			$view->setEventForm($event_form);
			$view->setPageId("event_add");

			$view->renderView();
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
		if ($this->validate('add_event')) {
			try {
				$post = & $this->input->post();
				$locations = $post['event_details_locations'];
				unset($post['event_details_locations']);

				//add event owner to post array
				$post['event_owner'] = $this->session->userdata('user_id');

				$event_model = new EventModel();
				$event_model->saveEvent($post, $locations);

				if ($event_model->getErrors()) {
					//load form displaying error message
					$this->setErrors($event_model->getErrors());
					$this->index();
				} else {
					redirect('/map/preview/'.$event_model->getEventDM()->getEventId());
				}
			} catch(Exception $e) {
				$this->setMessage($e->getMessage());
				$this->index();
			}
		} else {
			$this->index();
		}
	}

	public function transactionTest() {
		$model = new EventModel();
		$model->testTransactions();
	}

}

?>
