<?php

/**
 * this class is the controller class for adding and updating events as well as event locations and event details
 */
class Event extends N8_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->view('Event');
		$this->view = new EventVW();
		$this->generateCategoriesNav();
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
		try {
			$event_form = new Form();
			$event_form->setAction("event/addEvent");
			$event_form->setEnctype(Form::FORM_ENCTYPE_MULTIPART);
			$event_form->setId("event_add_form");

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setLabel("Event Name*");
			$field->setValue($this->input->post($field->getName()));
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setLabel("Start Date*");
			$field->setValue($this->input->post($field->getName()));
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setLabel("End Date*");
			$field->setValue($this->input->post($field->getName()));
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setLabel("Location Name*");
			$field->setName("event_details_locations[0][event_location_name]");
			$field->setValue($this->input->post($field->getName()));
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$error = form_error( 'event_details_locations[0][event_location_name]' );
			if(form_error( 'event_details_locations[0][lat_long]' )) {
				$error .= "<br />".form_error( 'event_details_locations[0][lat_long]' );
			}

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_HIDDEN);
			$field->setName("event_details_locations[0][lat_long]");
			$field->setId("event_details_locations[0][lat_long]");
			$field->setValue($this->input->post($field->getName()));

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setLabel("Address*");
			$field->setName("event_details_locations[0][event_address]");
			$field->setValue($this->input->post($field->getName()));
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setLabel("City*");
			$field->setName("event_details_locations[0][event_city]");
			$field->setValue($this->input->post($field->getName()));
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setLabel("State*");
			$field->setName("event_details_locations[0][event_state]");
			$field->setValue($this->input->post($field->getName()));
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setLabel("Zip*");
			$field->setName("event_details_locations[0][event_zip]");
			$field->setMaxLength("5");
			$field->setValue($this->input->post('event_details_locations[0]["event_zip"]'));
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setLabel("Country*");
			$field->setName("event_details_locations[0][event_country]");

			$country = ($this->input->post($field->getName())) ? $this->input->post($field->getName()) : "USA";
			$field->setValue($country);
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setLabel("Price (optional)");
			$field->setName("event_details_locations[0][event_cost]");
			$field->setValue($this->input->post($field->getName()));

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_CHECKBOX);
			$field->setLabel("Smoking");
			$field->setName("event_details_locations[0][smoking]");
			$field->setValue($this->input->post($field->getName()));

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_CHECKBOX);
			$field->setLabel("Food Available");
			$field->setName("event_details_locations[0][food]");
			$field->setValue($this->input->post($field->getName()));

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_SELECT);
			$field->setLabel("Age Range*");
			$field->setName("event_details_locations[0][age]");
			$field->addOption("", "");
			$field->addOption("18_35", "18-35");
			$field->addOption("30_50", "30-50");
			$field->setSelectedOption($this->input->post($field->getName()));
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_TEXTAREA);
			$field->setLabel("Description*");
			$field->setRows("3");
			$field->setCols("31");
			$field->setValue($this->input->post($field->getName()));
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$event_form->addField($field);

//			$field = Form::getNewField(Form_Field::FIELD_TYPE_SELECT);
//			$field->setLabel("Category*");
//			$field->addOption("", "");
//			$field->addOption("1", "Church Events");
//			$field->addOption("2", "Festivals");
//			$field->setSelectedOption($this->input->post($field->getName()));
//			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );
			$error_array = array('class' => 'error', 'id' => null, 'content' => form_error('category'));
			$categories_list = new CategoriesList();
			$categories_list->fetchCategories();
			$categories_list->buildSelectObject("Category*", 'category', $this->input->post('category'), $error_array);
			$categories_obj = $categories_list->getSelectObject();

			$event_form->addField($categories_obj);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_FILE);
			$field->setLabel("Event Image");
			$field->setAccept(Form_Field_Input_File::ACCEPT_TYPE_IMAGE);
			$field->setValue($this->input->post($field->getName()));

			$event_form->addField($field);

			$field = Form::getNewField(Form_Field::FIELD_TYPE_BUTTON);
			$field->setId("event_submit");
			$field->setContent("Add Event");

			$event_form->addField($field);

			$this->view->setErrors($this->getErrors());
			$this->view->setEventForm($event_form);
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
					redirect('/map/event_details/'.$event_model->getEventDM()->getEventId());
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
