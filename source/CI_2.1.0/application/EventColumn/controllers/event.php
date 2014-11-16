<?php

/**
 * this class is the controller class for adding and updating events as well as event locations and event details
 */
class event extends N8_Controller {

    private $form;

	public function __construct() {
		parent::__construct();

		$this->load->view('Event');
		$this->view = new EventVW();
//		$this->generateCategoriesNav();
	}

    public function index() {
        redirect('/event/create');
    }

	/**
	 * load the event add page
	 *
	 * @return void
	 * @access public
	 * @since 1.0
	 */
	public function create() {
		try {
			$this->auth->restrict();

			if($this->input->post('event_submit')) {
				$this->addEvent();
			}

            $this->startForm('event_add_form');
            $this->form->addField($this->buildEventNameField($this->getPostValue('event_name', 'title')));
			$this->form->addField($this->buildStartDateField($this->getPostValue('start_date', 'start date')));
			$this->form->addField($this->buildEndDateField($this->getPostValue('end_date', 'end date')));
            $this->form->addField($this->buildEventLocationNameField($this->getPostValue('event_details_locations[0][event_location_name]', 'venue')));
            $this->form->addField($this->buildEventLocationLatLong($this->getPostValue('event_details_locations[0][lat_long]', '')));
            $this->form->addField($this->buildEventLocationAddress($this->getPostValue('event_details_locations[0][event_address]', 'address')));
            $this->form->addField($this->buildEventLocationCity($this->getPostValue('event_details_locations[0][event_city]', 'city')));
            $this->form->addField($this->buildEventLocationState($this->input->post('event_details_locations[0][event_state]')));
            $this->form->addField($this->buildEventLocationZip($this->getPostValue('event_details_locations[0][event_zip]', 'zip')));
            $this->form->addField($this->buildEventLocationCountry());
            $this->form->addField($this->buildEventLocationCost($this->getPostValue('event_details_locations[0][event_cost]', 'admission')));
            $this->form->addField($this->buildEventLocationSmoking($this->input->post('event_details_locations[0][smoking]')));
            $this->form->addField($this->buildEventLocationFood($this->input->post('event_details_locations[0][food]')));
            $this->form->addField($this->buildEventLocationAge($this->input->post('event_details_locations[0][age]')));
            $this->form->addField($this->buildDescriptionField($this->getPostValue('description' , 'description')));
            $this->form->addField($this->buildCategoryField($this->input->post('category')));
            $this->form->addField($this->buildUserFileField());
            $this->form->addField($this->buildEventSubmitField());
            $this->form->addField($this->buildFormSubmitButton('add event'));

			$this->view->setErrors($this->getErrors());
			$this->view->setEventForm($this->form);
			$this->view->setPageId("event_add");
			$this->view->renderView();
		} catch (Exception $e) {
			log_message('error', $e->getMessage(), false);
			show_error("there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500);
		}
	}

    /**
     * edit an already existing event
     *
     * @todo add logic to update the event in the db when the form is submitted
     * @todo add event map and flyer on right pane
     * @access public
     * @param string $event_id
     * @return void
     */
    public function edit($event_id) {
        try {
            $this->auth->restrict();

            if($this->input->post('event_update')) {
                $this->updateEvent();
            }

            $event_id = EventMask::unmaskEventId($event_id);
            $event_iterator = new EventIterator($event_id);

            if($event_iterator->valid()) {
                $this->load->view('EventEdit');
                $this->view = new EventEditVW();
                $cache_key = CacheUtil::generateCacheKey('event_dm_');

                $this->buildUpdateForm($event_iterator, $cache_key);

                //cache event dm
                $cache_util = new CacheUtil();
                $cache_util->saveCache($cache_key,serialize($event_iterator->current()), 1200);

                $this->view->setErrors($this->getErrors());
                $this->view->setEventForm($this->form)
                        ->setEventName($event_iterator->getEventName())
                        ->setEventImage($event_iterator->getEventImage());
                $this->view->setPageId("event_update");
                $this->view->renderView();
            } else {
                //log error (don't throw exceptions b/c there is nowhere to catch them really)
            }
        } catch(Exception $e) {
            log_message('error', $e->getMessage(), false);
			show_error("there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500);
        }
    }

    /**
     * builds the event edit form
     *
     * @access public
     * @param EventIterator $event_iterator
     * @return void
     */
    private function buildUpdateForm(EventIterator $event_iterator, $cache_key) {
        $locations = $event_iterator->getEventLocations();

        $this->startForm('event_edit_form');
        $this->form->addField($this->buildEventIdField($event_iterator->getEventId()));
        $this->form->addField($this->buildCacheKeyField($cache_key));
        $this->form->addField($this->buildEventNameField($event_iterator->getEventName()));
        $this->form->addField($this->buildStartDateField($event_iterator->getEventStart()));
        $this->form->addField($this->buildEndDateField($event_iterator->getEventEnd()));

        $i = 0;
        foreach($locations as $location_dm) {
            $details_dm = $location_dm->getEventDetailsDM();
            $this->form->addField($this->buildEventLocationId($location_dm->getLocationId(), $i));
            $this->form->addField($this->buildEventLocationNameField($location_dm->getEventLocation(), $i));
            $this->form->addField($this->buildEventLocationLatLong($location_dm->getLatLong(), $i));
            $this->form->addField($this->buildEventLocationAddress($location_dm->getLocationAddress(), $i));
            $this->form->addField($this->buildEventLocationCity($location_dm->getLocationCity(), $i));
            $this->form->addField($this->buildEventLocationState($location_dm->getLocationState(), $i));
            $this->form->addField($this->buildEventLocationZip($location_dm->getLocationZip(), $i));
            $this->form->addField($this->buildEventLocationCountry($i));
            $this->form->addField($this->buildEventLocationCost($details_dm->getAdmission(), $i));
            $this->form->addField($this->buildEventLocationSmoking($details_dm->getSmoking(), $i));
            $this->form->addField($this->buildEventLocationFood($details_dm->getFoodAvailable(), $i));
            $this->form->addField($this->buildEventLocationAge($details_dm->getAgeRange(), $i));
            $i++;
        }

        $this->form->addField($this->buildDescriptionField($event_iterator->getEventDescription()));
        $this->form->addField($this->buildCategoryField($event_iterator->getEventCategory()));
        $this->form->addField($this->buildUserFileField());
        $this->form->addField($this->buildEventSubmitField('event_update', 'event_update'));
        $this->form->addField($this->buildFormSubmitButton('update event'));
    }

    /**
     * builds the event id hidden field
     *
     * @param int $event_id
     * @return \Form_Field_Hidden
     */
    private function buildEventIdField($event_id) {
        $field = new Form_Field_Hidden();
        $field->setName('event_id')
                ->setValue($event_id);

        return $field;
    }

    /**
     * builds the hidden cache key field
     *
     * @param string cache_key
     * @return \Form_Field_Hidden
     * @access private
     */
    private function buildCacheKeyField($cache_key) {
        $field = new Form_Field_Hidden();
        $field->setName('event_key')
                ->setValue($cache_key);

        return $field;
    }

    /**
     * builds the event submit button
     *
     * @access private
     * @return \Form_Field_Input_Button
     */
    private function buildFormSubmitButton($value = 'add event') {
        $field = new Form_Field_Input_Button();
        $field->setId('event_submit')
                ->setClass('ec_button')
                ->setValue($value);

        return $field;
    }

    /**
     * builds the event submit hidden field
     *
     * @access private
     * @return \Form_Field_Hidden
     */
    private function buildEventSubmitField($name = 'event_submit', $value = 'event_submit') {
        $field = new Form_Field_Hidden();
        $field->setName($name)
                ->setValue($value);

        return $field;
    }

    /**
     * builds the event userfile field
     *
     * @access private
     * @return \Form_Field_Input_File
     */
    private function buildUserFileField($file_location = null) {
        $field = new Form_Field_Input_File();
        $field->setName('userfile')
                ->setId('userfile')
                ->setLabel("event image")
                ->setAccept(Form_Field_Input_File::ACCEPT_TYPE_IMAGE);

        if($file_location) {
            $field->setValue($file_location);
        }

        return $field;
    }

    /**
     * builds the event category field
     *
     * @access private
     * @return type
     */
    private function buildCategoryField($selected_option) {
        $error_array = array('class' => 'error', 'id' => null, 'content' => form_error('category'));
        $categories_list = new CategoriesList();
        $categories_list->fetchCategories();
        $categories_list->buildSelectObject(
            "category", 'toggle_text', '-- Event Type --', $selected_option, $error_array
        );

        return $categories_list->getSelectObject();
    }

    /**
     * builds the event description field
     *
     * @access private
     * @return \Form_Field_Input_Textarea
     */
    private function buildDescriptionField($description) {
        $field = new Form_Field_Input_Textarea();
        $field->setName('description')
                ->setId('description')
                ->setClass('toggle_text')
                ->setValue($description)
                ->setRows("3")
                ->setCols("31");

        return $field;
    }

    /**
     * builds the location age field
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Select
     */
    private function buildEventLocationAge($selected_option, $location_index = 0) {
        $age_field = new Form_Field_Select();
        $age_field->setName('event_details_locations['.$location_index.'][age]')
                ->setId('event_details_locations['.$location_index.'][age]')
                ->setClass('toggle_text')
                ->addOption("", "-- Age Range --")
                ->addOption("18_35", "18-35")
                ->addOption("30_50", "30-50")
                ->setSelectedOption($selected_option);

		return $age_field;
    }

    /**
     * builds the location smoking field
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Input_Checkbox
     */
    private function buildEventLocationSmoking($checked = false, $location_index = 0) {
        $field = new Form_Field_Input_Checkbox();
        $field->setName('event_details_locations['.$location_index.'][smoking]')
                ->setClass('toggle_text')
                ->setChecked($checked)
                ->setLabel("smoking");

        return $field;
    }

    /**
     * builds the location food field
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Select
     */
    private function buildEventLocationFood($selected_option, $location_index = 0) {
        $field = new Form_Field_Select();
        $field->setName('event_details_locations['.$location_index.'][food]')
                ->setId('event_details_locations['.$location_index.'][food]')
                ->setClass('toggle_text');

        $field->addOption("", "-- Food & drinks --");
        $field->addOption("free", "Free");
        $field->addOption("on_sale", "On Sale");
        $field->addOption("no", "No");
        $field->setSelectedOption($selected_option);

        return $field;
    }

    /**
     * builds the location cost field
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Input
     */
    private function buildEventLocationCost($value, $location_index = 0) {
        $field = new Form_Field_Input();
        $field->setName('event_details_locations['.$location_index.'][event_cost]')
                ->setId('event_details_locations['.$location_index.'][event_cost]')
                ->setClass('toggle_text')
                ->setValue($value);

        return $field;
    }

    /**
     * builds the location country field
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Hidden
     */
    private function buildEventLocationCountry($location_index = 0) {
        $field = new Form_Field_Hidden();
        $field->setName('event_details_locations['.$location_index.'][event_country]')
                ->setId('event_details_locations['.$location_index.'][event_country]')
                ->setValue('USA');

        return $field;
    }

    /**
     * builds the location zip field
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Input
     */
    private function buildEventLocationZip($zip, $location_index = 0) {
        $field = new Form_Field_Input();
        $field->setName('event_details_locations['.$location_index.'][event_zip]')
                ->setId('event_details_locations['.$location_index.'][event_zip]')
                ->setClass('toggle_text')
                ->setMaxLength("5")
                ->setValue($zip);

        return $field;
    }

    /**
     * builds the location state field
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Select
     */
    private function buildEventLocationState($state, $location_index = 0) {
        $state_iterator = new StateIterator();
        $field = new Form_Field_Select();
        $field->setName('event_details_locations['.$location_index.'][event_state]')
              ->setId('event_details_locations['.$location_index.'][event_state]')
              ->setClass('toggle_text')
              ->addOption('', '-- Choose State --')
              ->setSelectedOption($state);

        while($state_iterator->valid()) {
            $field->addOption($state_iterator->current()->getStateCode(), $state_iterator->current()->getStateName());
            $state_iterator->next();
        }

        return $field;
    }

    /**
     * builds the location city field
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Input
     */
    private function buildEventLocationCity($city, $location_index = 0) {
        $field = new Form_Field_Input();
        $field->setName('event_details_locations['.$location_index.'][event_city]')
                ->setId('event_details_locations['.$location_index.'][event_city]')
                ->setClass('toggle_text')
                ->setValue($city);

        return $field;
    }

    /**
     * builds the location address field
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Input
     */
    private function buildEventLocationAddress($address, $location_index = 0) {
        $field = new Form_Field_Input();
        $field->setName('event_details_locations['.$location_index.'][event_address]')
                ->setId('event_details_locations['.$location_index.'][event_address]')
                ->setClass('toggle_text')
                ->setValue($address);

        return $field;
    }

    /**
     * builds the location lat/long field
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Hidden
     */
    private function buildEventLocationLatLong($lat_long, $location_index = 0) {
        $field = new Form_Field_Hidden();
        $field->setName('event_details_locations['.$location_index.'][lat_long]')
                ->setValue($lat_long);

        return $field;
    }

    /**
     * builds the location name field (venue)
     *
     * @access private
     * @param int $location_index
     * @return \Form_Field_Input
     */
    private function buildEventLocationNameField($venue, $location_index = 0) {
        $field = new Form_Field_Input();
        $field->setName('event_details_locations['.$location_index.'][event_location_name]')
                ->setId('event_details_locations['.$location_index.'][event_location_name]')
                ->setClass('toggle_text')
                ->setValue($venue);

        return $field;
    }

    private function buildEventLocationId($location_id, $location_index = 0) {
        $field = new Form_Field_Hidden();
        $field->setName('event_details_locations['.$location_index.'][location_id]')
                ->setValue($location_id);

        return $field;
    }
    /**
     * builds the end date field
     *
     * @access private
     * @return \Form_Field_Input
     */
    private function buildEndDateField($end_date) {
        $field = new Form_Field_Input();
        $field->setName('end_date')
                ->setId('end_date')
                ->setClass('toggle_text')
                ->setValue($end_date);

        return $field;
    }

    /**
     * builds the start date field
     *
     * @access private
     * @return \Form_Field_Input
     */
    private function buildStartDateField($start_date) {
        $field = new Form_Field_Input();
        $field->setName('start_date')
                ->setId('start_date')
                ->setClass('toggle_text')
                ->setValue($start_date);

        return $field;
    }

    /**
     * builds the event name field
     *
     * @access private
     * @return \Form_Field_Input
     */
    private function buildEventNameField($name) {
        $field = new Form_Field_Input();
        $field->setName('event_name')
                ->setId('event_name')
                ->setClass('toggle_text')
                ->setValue($name);

        return $field;
    }

    /**
     * starts the event form
     *
     * @access private
     * @return void
     */
    private function startForm($form_id) {
        $this->form = new Form();
        $this->form->setAction('')
                ->setMethod('post')
                ->setId($form_id)
                ->setEnctype(Form::FORM_ENCTYPE_MULTIPART);
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
     * updates an existing event
     *
     * @return void
     * @access protected
     */
    protected function updateEvent() {
		try {
			if ($this->validate('add_event')) {
                $upload_data = $this->uploadFlyer();
				$post = & $this->input->post();
				$locations = $post['event_details_locations'];
				unset($post['event_details_locations']);

                $post['event_image'] = substr($upload_data['full_path'], 21);

				$event_model = new EventModel();
				$event_model->saveEvent($post, $locations);

				if ($event_model->getErrors()) {
					$this->setErrors($event_model->getErrors());
				} else {
					redirect('/map/event_details/'.  EventMask::maskEventId($event_model->getEventDM()->getEventId()) . '/true');
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
