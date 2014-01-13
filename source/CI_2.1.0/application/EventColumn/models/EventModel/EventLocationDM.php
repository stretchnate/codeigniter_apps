<?php

class EventModel_EventLocationDM extends BaseDM {

	private $location_id;
	private $event_location;
	private $lat_long;
	private $location_address;
	private $location_city;
	private $location_state;
	private $location_zip;
	private $location_country;
	private $event_cost;
	private $event_id;
	private $event_details_id;
	private $event_details_dm;

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
	 * EventLocationDM does not load itself because a location cannot exist by itself. it must have an
	 * event to exist with. So we use the EventDM to load it's own EventLocationDM's
	 *
	 * @param  int $id (only available to satisfy the signature from the parent class)
	 * @access public
	 * @return void
	 * @since  1.0
	 */
	public function load($id = false) {

	}

	/**
	 * loads the event details for the location.
	 * @todo add logic to populate the eventDetailsDM if an event_details_id does not exist.
	 *
	 * @access public
	 * @return void
	 * @since  1.0
	 */
	public function loadEventDetailsDM() {
		$this->event_details_dm = new EventModel_EventDetailsDM();

		if (empty($this->event_details_id)) {
			$this->event_details_dm->load($this->event_details_id);
		}
	}

	/**
	 * save the event location to the database.
	 *
	 * @access public
	 * @return mixed
	 * @since  1.0
	 */
	public function save() {
		if ($this->location_id > 0) {
			if (!$this->update()) {
				$this->setError("There was a problem updating the event location for  " . $this->location_id);
			}
		} else {
			$this->insert();
			$this->insert_id = $this->db->insert_id();
			return $this->insert_id;
		}
	}

	/**
	 * update the EVENT_LOCATIONS table
	 *
	 * @access private
	 * @return boolean
	 * @since  1.0
	 */
	protected function update() {
		try {
			$sets = array();
			$sets['event_location'] = $this->event_location;
			$sets['lat_long'] = $this->lat_long;
			$sets['location_address'] = $this->location_address;
			$sets['location_city'] = $this->location_city;
			$sets['location_state'] = $this->location_state;
			$sets['location_zip'] = $this->location_zip;
			$sets['location_country'] = $this->location_country;
			$sets['event_cost'] = $this->event_cost;
			$sets['event_id'] = $this->event_id;
			$sets['event_details_id'] = $this->event_details_id;

			$this->db->where("location_id", $this->location_id);
			$this->db->update("EVENT_LOCATIONS", $sets);
			return true;
		} catch (Exception $e) {
			$this->setError("There was a problem updating the event location [" . $this->location_id . "][" . $e->getMessage() . "]");
			return false;
		}
	}

	/**
	 * insert a new EVENT_LOCATIONS row
	 *
	 * @access private
	 * @return unknown
	 * @since  1.0
	 */
	protected function insert() {
		$values = array();

		$values['event_location'] = $this->event_location;
		$values['lat_long'] = $this->lat_long;
		$values['location_address'] = $this->location_address;
		$values['location_city'] = $this->location_city;
		$values['location_state'] = $this->location_state;
		$values['location_zip'] = $this->location_zip;
		$values['location_country'] = $this->location_country;
		$values['event_cost'] = $this->event_cost;
		$values['event_id'] = $this->event_id;
		$values['event_details_id'] = $this->event_details_id;

		return $this->db->insert("EVENT_LOCATIONS", $values);
	}

	/**
	 * gets the location_id
	 *
	 * @return int
	 * @since  1.0
	 */
	public function getLocationId() {
		return $this->location_id;
	}

	/**
	 * sets the location_id
	 *
	 * @param  int
	 * @return Object
	 * @since  1.0
	 */
	public function setLocationId($location_id) {
		$this->location_id = $location_id;
		return $this;
	}

	/**
	 * gets the event_location
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getEventLocation() {
		return $this->event_location;
	}

	/**
	 * sets the category_name
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setEventLocation($event_location) {
		$this->event_location = strtoupper($event_location);
		return $this;
	}

	/**
	 * gets the lat_long value for the address
	 *
	 * @return string
	 */
	public function getLatLong() {
		return $this->lat_long;
	}

	/**
	 * sets the lat_long value
	 *
	 * @param string $lat_long
	 * @return \EventModel_EventLocationDM
	 */
	public function setLatLong($lat_long) {
		$this->lat_long = $lat_long;
		return $this;
	}
	/**
	 * gets the location_address
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getLocationAddress() {
		return $this->location_address;
	}

	/**
	 * sets the location_address
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setLocationAddress($location_address) {
		$this->location_address = strtoupper($location_address);
		return $this;
	}

	/**
	 * gets the location_city
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getLocationCity() {
		return $this->location_city;
	}

	/**
	 * sets the location_city
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setLocationCity($location_city) {
		$this->location_city = strtoupper($location_city);
		return $this;
	}

	/**
	 * gets the location_state
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getLocationState() {
		return $this->location_state;
	}

	/**
	 * sets the location_state
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setLocationState($location_state) {
		$this->location_state = strtoupper($location_state);
		return $this;
	}

	/**
	 * gets the location_zip
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getLocationZip() {
		return $this->location_zip;
	}

	/**
	 * sets the location_zip
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setLocationZip($location_zip) {
		$this->location_zip = $location_zip;
		return $this;
	}

	/**
	 * gets the location_country
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getLocationCountry() {
		return $this->location_country;
	}

	/**
	 * sets the location_country
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setLocationCountry($location_country) {
		$this->location_country = strtoupper($location_country);
		return $this;
	}

	/**
	 * gets the event_cost
	 *
	 * @return Float
	 * @since  1.0
	 */
	public function getEventCost() {
		return $this->event_cost;
	}

	/**
	 * sets the event_cost
	 *
	 * @param  Float
	 * @return Object
	 * @since  1.0
	 */
	public function setEventCost($event_cost) {
		$this->event_cost = $event_cost;
		return $this;
	}

	/**
	 * gets the event_details_dm
	 *
	 * @return Object EventModel_EventDetailsDM
	 * @since  1.0
	 */
	public function getEventDetailsDM() {
		return $this->event_details_dm;
	}

	/**
	 * sets the event_details_dm
	 *
	 * @param  Object
	 * @return Object
	 * @since  1.0
	 */
	public function setEventDetailsDM(EventModel_EventDetailsDM $event_details_dm) {
		$this->event_details_dm = $event_details_dm;
		return $this;
	}

	/**
	 * gets the event_id
	 *
	 * @return Int
	 * @since  1.0
	 */
	public function getEventId() {
		return $this->event_id;
	}

	/**
	 * sets the event_id
	 *
	 * @param  $int
	 * @return Object
	 * @since  1.0
	 */
	public function setEventId($event_id) {
		$this->event_id = $event_id;
		return $this;
	}

	/**
	 * gets the event_details_id
	 *
	 * @return Int
	 * @since  1.0
	 */
	public function getEventDetailsId() {
		return $this->event_details_id;
	}

	/**
	 * sets the event_details_id
	 *
	 * @param  $int
	 * @return Object
	 * @since  1.0
	 */
	public function setEventDetailsId($event_details_id) {
		$this->event_details_id = $event_details_id;
		return $this;
	}

}

?>
