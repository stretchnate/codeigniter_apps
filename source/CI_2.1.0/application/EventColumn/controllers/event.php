<?php

/**
 * this class is the controller class for adding and updating events as well as event locations and event details
 */
class Event extends CI_Controller {

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
			$event_form->setAction("event/create");
			$event_form->setEnctype(Form::FORM_ENCTYPE_MULTIPART);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Event Name*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-name");
			$field->setName("event-name");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("When*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-when");
			$field->setName("event-when");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Location Name*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-location-name");
			$field->setName("event-location-name");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Address*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-address");
			$field->setName("event-address");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("City*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-city");
			$field->setName("event-city");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("State*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-state");
			$field->setName("event-state");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Zip*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-zip");
			$field->setName("event-zip");
			$field->setMaxLength("5");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Country*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-country");
			$field->setName("event-country");
			$field->setValue("USA");
			$field->setDisabled("disabled");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_TEXTAREA);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Details");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-details");
			$field->setName("event-details");
			$field->setRows("3");
			$field->setCols("31");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_SELECT);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Category*");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-category");
			$field->setName("event-category");
			$field->addOption("option1", "Option 1");
			$field->addOption("option2", "Option 2");

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_FILE);
			$field->setContainerClass("event-form-field");
			$field->setLabel("Event Image");
			$field->setLabelContainerClass("event-form-label");
			$field->setFieldContainerClass("field-container");
			$field->setId("event-image");
			$field->setName("event-image");
			$field->setAccept(Form_Field_Input_File::ACCEPT_TYPE_IMAGE);

			$event_form->addField($field);

			$view = new EventVW();
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

	}

	public function transactionTest() {
		$model = new EventModel();
		$model->testTransactions();
	}

}

?>
