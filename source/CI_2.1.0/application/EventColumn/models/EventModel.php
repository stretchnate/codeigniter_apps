<?php
/**
 * Business model for handling events
 */
class EventModel extends N8_Model {

	protected $event_dm;

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
			$this->saveNewEvent($event, $event_details_locations);

			if (!$this->event_dm->getEventId()) {
				$this->setError("There was a problem saving your event");
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
		$this->event_dm = new EventModel_EventDM();
		$this->event_dm->setEventOwner($event['event_owner']);
		$this->event_dm->setEventName($event['event_name']);
		$this->event_dm->setEventStartDatetime($event['start_date']);
		$this->event_dm->setEventEndDatetime($event['end_date']);
		$this->event_dm->setEventDescription($event['description']);
		$this->event_dm->setEventCategory($event['category']);

		if(isset($event['event_image'])) {
			$this->event_dm->setEventImage($event['event_image']);
		}

		//save the event to the db
		$this->event_dm->save();

		//create each details object and each location object.
		foreach ($event_details_locations as $event_details_location) {
			$details = new EventModel_EventDetailsDM();
			if(isset($event_details_location['smoking'])) {
				$details->setSmoking($event_details_location['smoking']);
			}

			$details->setFoodAvailable($event_details_location['food']);
			$details->setAgeRange($event_details_location['age']);
            $details->setAdmission($event_details_location['event_cost']);

			//save the details
			$details->save();

			$location = new EventModel_EventLocationDM();
			$location->setEventLocation($event_details_location['event_location_name']);
			$location->setLatLong($event_details_location['lat_long']);
			$location->setLocationAddress($event_details_location['event_address']);
			$location->setLocationCity($event_details_location['event_city']);
			$location->setLocationState($event_details_location['event_state']);
			$location->setLocationZip($event_details_location['event_zip']);
			$location->setLocationCountry($event_details_location['event_country']);

			if($event_details_location['event_cost']) {
				$location->setEventCost($event_details_location['event_cost']);
			}

			$location->setEventId($this->event_dm->getEventId());
			$location->setEventDetailsId($details->getEventDetailsId());
			$location->setEventDetailsDM($details);
			$location->save();

			$this->event_dm->addEventLocation($location);
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

		$this->event_dm->setEventOwner($event['event_owner']);
		$this->event_dm->setEventName($event['event_name']);
		$this->event_dm->setEventStartDatetime($event['event_start_datetime']);
		$this->event_dm->setEventEndDatetime($event['event_end_datetime']);
		$this->event_dm->setEventDescription($event['event_description']);
		$this->event_dm->setEventCategory($event['event_category']);
		$this->event_dm->setEventImage($event['event_image']);

		//save the event to the db
		$this->event_dm->save();

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
		if (!empty($this->event_dm)) {
			//get location by id
			$location = $this->event_dm->getLocationById($event_details_location['location_id']);
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
		$location->setEventLocation($event_details_location['lat_long']);
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
		$this->event_dm = new EventModel_EventDM();
		$this->event_dm->load($event_id);
	}

	public function testTransactions() {
		$dm = new testDM();
		$this->transactionStart();
		$dm->insert1();
		$dm->insert2();
		$this->transactionEnd();
	}

	public function getEventDM() {
		return $this->event_dm;
	}
}

?>
