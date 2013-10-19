<?php

class EventModel extends N8_Model {

	protected $event;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * saves the event in the database
	 *
	 * @param array $event
	 * @param array $event_details_locations (two dimensional i.e. $event_locations = array(array('event_location' => 'timbuktu'...), array('event_location' => 'WalMart'...))
	 * @return mixed
	 * @access public
	 * @since 1.0
	 */
	public function saveEvent(array $event, array $event_details_locations) {
		$this->transactionStart();

		if (empty($event['event_id'])) {
			$event_id = $this->saveNewEvent($event, $event_details_locations);
			$this->event->load($event_id);

			if (!$this->event->getEventId()) {
				$this->setError("There was a problem saving your event", N8_Error::ERROR);
			}
		} else {
			$this->updateEvent($event, $event_details_locations);
		}

		$this->transactionEnd();
	}

	/**
	 * add a new event
	 *
	 * @param array $event
	 * @param array $event_details_locations
	 * @return void
	 * @access protected
	 * @since 1.0
	 */
	protected function saveNewEvent(array $event, array $event_details_locations) {
		//create the event object
		$this->event = new EventModel_EventDM();
		$this->event->setEventOwner($event['event_owner']);
		$this->event->setEventName($event['event_name']);
		$this->event->setEventStartDatetime($event['event_start_datetime']);
		$this->event->setEventEndDatetime($event['event_end_datetime']);
		$this->event->setEventDescription($event['event_description']);
		$this->event->setEventCategory($event['event_category']);
		$this->event->setEventImage($event['event_image']);

		//save the event to the db
		$this->event->save();

		//create each details object and each location object.
		foreach ($event_details_locations as $event_details_location) {
			$details = new EventModel_EventDetailsDM();
			$details->setSmoking($event_details_location['smoking']);
			$details->setFoodAvailable($event_details_location['food_available']);
			$details->setAgeRange($event_details_location['age_range']);

			//save the details
			$details->save();

			$location = new EventModel_EventLocationDM();
			$location->setEventLocation($event_details_location['event_location']);
			$location->setLocationAddress($event_details_location['location_address']);
			$location->setLocationCity($event_details_location['location_city']);
			$location->setLocationState($event_details_location['location_state']);
			$location->setLocationZip($event_details_location['location_zip']);
			$location->setLocationCountry($event_details_location['location_country']);
			$location->setEventCost($event_details_location['event_cost']);
			$location->setEventId($event->getEventId());
			$location->setEventDetailsId($details->getEventDetailsId());
			$location->setEventDetailsDM($details);
			$location->save();

			$this->event->addLocation($location);
		}
	}

	/**
	 * update existing event, locations and details
	 *
	 * @param array $event
	 * @param array $event_details_locations
	 * @return unknown
	 * @access protected
	 * @since 1.0
	 */
	protected function updateEvent(array $event, array $event_details_locations) {
		//create the event object
		$this->loadEvent($event['event_id']);

		$this->event->setEventOwner($event['event_owner']);
		$this->event->setEventName($event['event_name']);
		$this->event->setEventStartDatetime($event['event_start_datetime']);
		$this->event->setEventEndDatetime($event['event_end_datetime']);
		$this->event->setEventDescription($event['event_description']);
		$this->event->setEventCategory($event['event_category']);
		$this->event->setEventImage($event['event_image']);

		//save the event to the db
		$this->event->save();

		//create each details object and each location object.
		foreach ($event_details_locations as $event_details_location) {
			$this->updateLocationDetails($event_details_location);
		}
	}

	/**
	 * update an existing event location and details
	 *
	 * @param array $event_details_location
	 * @return void
	 * @access public
	 * @since 1.0
	 */
	public function updateLocationDetails(array $event_details_location) {
		if (!empty($this->event)) {
			//get location by id
			$location = $this->event->getLocationById($event_details_location['location_id']);
		} else {
			$location = new EventModel_EventLocationDM();
			$location->load($event_details_location['location_id']);
		}

		$details = $location->getEventDetailsDM();

		$details->setSmoking($event_details_location['smoking']);
		$details->setFoodAvailable($event_details_location['food_available']);
		$details->setAgeRange($event_details_location['age_range']);

		//save the details
		$details->save();

		$location->setEventLocation($event_details_location['event_location']);
		$location->setLocationAddress($event_details_location['location_address']);
		$location->setLocationCity($event_details_location['location_city']);
		$location->setLocationState($event_details_location['location_state']);
		$location->setLocationZip($event_details_location['location_zip']);
		$location->setLocationCountry($event_details_location['location_country']);
		$location->setEventCost($event_details_location['event_cost']);
		$location->setEventDetailsId($details->getEventDetailsId());

		//save the location
		$location->save();
	}

	/**
	 * loads the event, it's locations and details
	 *
	 * @param  int $event_id
	 * @return void
	 * @access public
	 * @since 1.0
	 */
	public function loadEvent($event_id) {
		$this->event = new EventModel_EventDM();
		$this->event->load($event_id);
	}

	public function testTransactions() {
		$dm = new testDM();
		$this->transactionStart();
		$dm->insert1();
		$dm->insert2();
		$this->transactionEnd();
	}

}

?>
