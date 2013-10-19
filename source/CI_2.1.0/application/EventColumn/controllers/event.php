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
		$this->load->view('Event');

		try {
			$event_form = new Form();
			$event_form->setAction("event/addEvent");
			$event_form->setEnctype(Form::FORM_ENCTYPE_MULTIPART);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_HIDDEN);
			$field->setName("event_owner");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Event Name*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-name");
			$field->setName("event_name");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Start Date*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-start");
			$field->setName("event_start_datetime");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("End Date*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-end");
			$field->setName("event_end_datetime");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Location Name*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-location-name");
			$field->setName("event_details_locations[0][event_location_name]");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Address*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-address");
			$field->setName("event_details_locations[0][event_address]");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("City*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-city");
			$field->setName("event_details_locations[0][event_city]");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("State*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-state");
			$field->setName("event_details_locations[0][event_state]");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Zip*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-zip");
			$field->setName("event_details_locations[0][event_zip]");
			$field->setMaxLength("5");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Country*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-country");
			$field->setName("event_details_locations[0][event_country]");
			$field->setValue("USA");
//			$field->setDisabled("disabled");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_CHECKBOX);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Smoking*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-location-smoking");
			$field->setName("event_details_locations[0][smoking]");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_CHECKBOX);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Food Available*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-location-food");
			$field->setName("event_details_locations[0][food]");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_SELECT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Age Range*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-location-age");
			$field->setName("event_details_locations[0][age]");
			$field->addOption("18_35", "18-35");
			$field->addOption("30_50", "30-50");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_TEXTAREA);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Description");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-description");
			$field->setName("event_description");
			$field->setRows("3");
			$field->setCols("31");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_SELECT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Category*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-category");
			$field->setName("event_category");
			$field->addOption("1", "Church Events");
			$field->addOption("2", "Festivals");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_FILE);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Event Image");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-image");
			$field->setName("event_image");
			$field->setAccept(Form_Field_Input_File::ACCEPT_TYPE_IMAGE);

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_SUBMIT);
			$field->setContainerClass("event-form-field");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-submit");
			$field->setValue("Change this to a button and add Javascript");

			$event_form->addField($field);

			$view = new EventVW();
			$view->setErrors($this->getErrors());
			$view->setEventForm($event_form);

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
			$post = & $this->input->post();
			$locations = $post['event_details_locations'];
			unset($post['event_details_locations']);

//			clearLog();
//			dbo_arr("post", $post);
			$event_model = new EventModel();
			$event_model->saveEvent($post, $locations);

			if ($event_model->getErrors()) {
				//load form displaying error message
				$this->setErrors($event_model->getErrors());
				$this->index();
			} else {
				die("event was added");
			}
		} else {
			echo validation_errors();
			die("event failed to validate");
		}
	}

	public function transactionTest() {
		$model = new EventModel();
		$model->testTransactions();
	}

}

?>
