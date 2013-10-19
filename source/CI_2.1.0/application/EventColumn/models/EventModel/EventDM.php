<?php

class EventModel_EventDM extends BaseDM {

	private $event_id;
	private $event_owner;
	private $event_name;
	private $event_start_datetime;
	private $event_end_datetime;
	private $event_description;
	private $event_category;
	private $event_image;
	private $event_locations = array();

	/**
	 * class construct method
	 *
	 * @access public
	 * @return Object
	 * @since  1.0
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Load the event based on the event ID
	 *
	 * @param  int $id
	 * @access public
	 * @return void
	 * @throws Exception
	 * @since  1.0
	 */
	public function load($id) {
		$query = $this->db->get_where("EVENTS", array("event_id" => $id));

		$events = $query->result();

		if (is_array($events)) {
			$events = $events[0];

			$this->event_id = $events['event_id'];
			$this->event_owner = $events['event_owner'];
			$this->event_name = $events['event_name'];
			$this->event_start_datetime = $events['event_start_datetime'];
			$this->event_end_datetime = $events['event_end_datetime'];
			$this->event_description = $events['event_description'];
			$this->event_category = $events['event_category'];
			$this->event_image = $events['event_image'];

			$this->loadEventLocations();
		} else {
			throw new Exception("unable to load event [" . $this->event_id . "]");
		}
	}

	/**
	 * loads the event locatoins for the event.
	 *
	 * @access public
	 * @return void
	 * @since  1.0
	 */
	public function loadEventLocations() {
		$query = $this->db->get_where("EVENT_LOCATIONS", array("event_id" => $this->event_id));

		$locations = $query->result();

		foreach ($locations as $location) {
			$location = new EventColumn_EventModel_EventLocationDM();
			$location->setLocationId($location['location_id'])
				   ->setEventLocation($location['event_location'])
				   ->setLocationAddress($location['location_address'])
				   ->setLocationCity($location['location_city'])
				   ->setLocationState($location['location_state'])
				   ->setLocationZip($location['locatoin_zip'])
				   ->setLocationCountry($location['location_country'])
				   ->setEventCost($location['event_cost'])
				   ->setEventId($location['event_id'])
				   ->setEventDetailsId($location['event_details_id']);

			$location->loadEventDetailsDM();

			$this->addLocation($location);
		}
	}

	/**
	 * save the event to the database.
	 *
	 * @access public
	 * @return mixed
	 * @since  1.0
	 */
	public function save() {
		if ($this->event_id > 0) {
			if (!$this->update()) {
				$this->setError("There was a problem updating the event [" . $this->event_id . "]");
			}
		} else {
			$this->insert();
			$this->event_id = $this->db->insert_id();
			return $this->event_id;
		}
	}

	/**
	 * update the EVENTS table
	 *
	 * @access private
	 * @return boolean
	 * @since  1.0
	 */
	protected function update() {
		try {
			$sets = array();
			$sets['event_owner'] = $this->event_owner;
			$sets['event_name'] = $this->event_name;
			$sets['event_start_datetime'] = $this->event_start_datetime;
			$sets['event_end_datetime'] = $this->event_end_datetime;
			$sets['event_description'] = $this->event_description;
			$sets['event_category'] = $this->event_category;
			$sets['event_image'] = $this->event_image;

			$this->db->where("event_id", $this->event_id);
			$this->db->update("EVENTS", $sets);
			return true;
		} catch (Exception $e) {
			$this->setError("There was a problem updating the event [" . $this->event_id . "][" . $e->getMessage() . "]");
			return false;
		}
	}

	/**
	 * insert a new EVENT_CATEGORIES row
	 *
	 * @access private
	 * @return unknown
	 * @since  1.0
	 */
	protected function insert() {
		$values = array();

		$values['event_owner'] = $this->event_owner;
		$values['event_name'] = $this->event_name;
		$values['event_start_datetime'] = $this->event_start_datetime;
		$values['event_end_datetime'] = $this->event_end_datetime;
		$values['event_description'] = $this->event_description;
		$values['event_category'] = $this->event_category;
		$values['event_image'] = $this->event_image;

		return $this->db->insert("EVENTS", $values);
	}

	/**
	 * returns the location based on the id provided
	 *
	 * @param type $location_id
	 * @return Object EventColumn_EventModel_EventLocationDM
	 * @access public
	 * @since 1.0
	 */
	public function getLocationById($location_id) {
		foreach ($this->event_locations as $location) {
			if ($location->getLocationId() == $location_id) {
				return $location;
			}
		}
	}

	/**
	 * gets the event_id
	 *
	 * @return int
	 * @since  1.0
	 */
	public function getEventId() {
		return $this->event_id;
	}

	/**
	 * gets the event_owner
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getEventOwner() {
		return $this->event_owner;
	}

	/**
	 * sets the event_owner
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setEventOwner($event_owner) {
		$this->event_owner = $event_owner;
		return $this;
	}

	/**
	 * gets the event_name
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getEventName() {
		return $this->event_name;
	}

	/**
	 * sets the event_name
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setEventName($event_name) {
		$this->event_name = $event_name;
		return $this;
	}

	/**
	 * gets the event_start_datetime
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getEventStartDatetime() {
		return $this->event_start_datetime;
	}

	/**
	 * sets the event_start_datetime
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setEventStartDateTime($event_start_datetime) {
		$this->event_start_datetime = $event_start_datetime;
		return $this;
	}

	/**
	 * gets the event_end_datetime
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getEventEndDatetime() {
		return $this->event_end_datetime;
	}

	/**
	 * sets the event_end_datetime
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setEventEndDatetime($event_end_datetime) {
		$this->event_end_datetime = $event_end_datetime;
		return $this;
	}

	/**
	 * gets the event_description
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getEventDescription() {
		return $this->event_description;
	}

	/**
	 * sets the event_description
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setEventDescription($event_description) {
		$this->event_description = $event_description;
		return $this;
	}

	/**
	 * gets the event_category
	 *
	 * @return int
	 * @since  1.0
	 */
	public function getEventCategory() {
		return $this->event_category;
	}

	/**
	 * sets the event_category
	 *
	 * @param  int
	 * @return Object
	 * @since  1.0
	 */
	public function setEventCategory($event_category) {
		$this->event_category = $event_category;
		return $this;
	}

	/**
	 * gets the event_image/flyer
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getEventImage() {
		return $this->event_image;
	}

	/**
	 * sets the event_image
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setEventImage($event_image) {
		$this->event_image = $event_image;
		return $this;
	}

	/**
	 * gets the event_locations
	 *
	 * @return array
	 * @since  1.0
	 */
	public function getEventLocations() {
		return $this->event_locations;
	}

	/**
	 * sets the event_locations
	 *
	 * @param  array
	 * @return Object
	 * @since  1.0
	 */
	public function setEventLocations(array $event_locations) {
		$this->event_locations = $event_locations;
		return $this;
	}

	/**
	 * sets the event_locations
	 *
	 * @param  Object EventColumn_EventModel_EventLocationDM
	 * @return Object
	 * @since  1.0
	 */
	public function addEventLocation(EventColumn_EventModel_EventLocationDM $event_location) {
		$this->event_locations[] = $event_location;
		return $this;
	}

}

?>
