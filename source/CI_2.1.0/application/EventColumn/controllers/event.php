<?php

/**
 * this class is the controller class for adding and updating events as well as event locations and event details
 */
class Event extends N8_Controller {

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
		$this->auth->restrict();
		try {
			$event_form = new Form();
			$event_form->setAction("event/addEvent");
			$event_form->setEnctype(Form::FORM_ENCTYPE_MULTIPART);
			$event_form->setId("event_add_form");

			$event_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT, 'event_name', 'Event Name') );
			$event_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT, 'start_date', 'Start Date') );
			$event_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT, 'end_date', 'End Date') );
			$location_field = $this->buildField(Form_Field::FIELD_TYPE_INPUT,
												'event_details_locations[0][event_location_name]',
												'Location Name');

			$error = form_error( 'event_details_locations[0][event_location_name]' );
			if(form_error( 'event_details_locations[0][lat_long]' )) {
				$error .= "<br />".form_error( 'event_details_locations[0][lat_long]' );
			}

			$event_form->addField($location_field);

			$event_form->addField( $this->buildField(Form_Field::FIELD_TYPE_HIDDEN,
													'event_details_locations[0][lat_long]',
													'') );

			$event_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT,
													'event_details_locations[0][event_address]',
													'Address') );

			$event_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT,
													'event_details_locations[0][event_city]',
													'City') );

			$event_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT,
													'event_details_locations[0][event_state]',
													'State') );

			$zip_field = $this->buildField(Form_Field::FIELD_TYPE_INPUT,
													'event_details_locations[0][event_zip]',
													'Zip');
			$zip_field->setMaxLength("5");
			$event_form->addField($zip_field);

			$event_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT,
													'event_details_locations[0][event_country]',
													'Country') );

			$event_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT,
													'event_details_locations[0][event_cost]',
													'Price (optional)') );

			$smoke_field = Form::getNewField(Form_Field::FIELD_TYPE_CHECKBOX);
            $smoke_field->setLabel("Smoking");
            $smoke_field->setName("event_details_locations[0][smoking]");
			$smoke_field->setClass('toggle_text');
            $smoke_field->setValue($this->input->post($smoke_field->getName()));
			$smoke_field->addErrorLabel( 'error', null, form_error( $smoke_field->getName() ) );

			$event_form->addField($smoke_field);

			$food_field = Form::getNewField(Form_Field::FIELD_TYPE_CHECKBOX);
            $food_field->setLabel("Food Available");
            $food_field->setName("event_details_locations[0][food]");
			$food_field->setClass('toggle_text');
            $food_field->setValue($this->input->post($food_field->getName()));
			$food_field->addErrorLabel( 'error', null, form_error( $food_field->getName() ) );

			$event_form->addField($food_field);

			$age_field = Form::getNewField(Form_Field::FIELD_TYPE_SELECT);
			$age_field->setClass('toggle_text');
			$age_field->setName("event_details_locations[0][age]");
			$age_field->setId("event_details_locations[0][age]");
			$age_field->addOption("", "Age Range*");
			$age_field->addOption("18_35", "18-35");
			$age_field->addOption("30_50", "30-50");
			$age_field->setSelectedOption($this->input->post($age_field->getName()));
			$age_field->addErrorLabel( 'error', null, form_error( $age_field->getName() ) );

			$event_form->addField($age_field);

			$description_field = $this->buildField(Form_Field::FIELD_TYPE_TEXTAREA, 'description', 'Description');
			$description_field->setRows("3");
			$description_field->setCols("31");

			$event_form->addField($description_field);

			$error_array = array('class' => 'error', 'id' => null, 'content' => form_error('category'));
			$categories_list = new CategoriesList();
			$categories_list->fetchCategories();
			$categories_list->buildSelectObject("Category*", 'category', $this->input->post('category'), $error_array);
			$categories_obj = $categories_list->getSelectObject();

			$event_form->addField($categories_obj);

			$file_field = Form::getNewField(Form_Field::FIELD_TYPE_FILE);
			$file_field->setLabel("Event Image");
			$file_field->setAccept(Form_Field_Input_File::ACCEPT_TYPE_IMAGE);
			$file_field->setValue($this->input->post($file_field->getName()));

			$event_form->addField($file_field);

			$button = Form::getNewField(Form_Field::FIELD_TYPE_BUTTON);
			$button->setId("event_submit");
			$button->setContent("Add Event");

			$event_form->addField($button);

			$this->view->setErrors($this->getErrors());
			$this->view->setEventForm($event_form);
			$this->view->setPageId("event_add");

			$this->view->renderView();
		} catch (Exception $e) {
			log_message('error', $e->getMessage(), false);
			show_error("there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500);
		}
	}

	private function buildField($type, $name_id, $default_label) {
		$field = Form::getNewField($type);
		$field->setClass('toggle_text');
		$field->setName($name_id)->setId($name_id);
		$name_value = ($this->input->post($field->getName()) != '') ?
								$this->input->post($field->getName()) :
								$default_label;

		$field->setValue($name_value);
		$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

		return $field;
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
					redirect('/map/event_details/'.  EventMask::maskEventId($event_model->getEventDM()->getEventId()));
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
