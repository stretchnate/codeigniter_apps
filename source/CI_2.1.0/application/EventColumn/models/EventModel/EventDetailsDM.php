<?php

class EventColumn_EventModel_EventDetailsDM extends BaseDM {

	private $event_details_id;
	private $smoking;
	private $food_available;
	private $age_range;

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
	 * loads the event details from the EVENT_DETAILS table.
	 *
	 * @param  int $event_details_id
	 * @access public
	 * @return void
	 * @throws Exception
	 * @since  1.0
	 */
	public function load($id) {
		$query = $this->db->get_where("EVENT_DETAILS", array("event_details_id" => $id));

		$event_details = $query->result();

		if (is_array($event_details)) {
			$event_details = $event_details[0];

			foreach ($event_details as $column => $value) {
				if (property_exists($this, $column)) {
					$this->$column = $value;
				}
			}
		} else {
			throw new Exception("unable to load details for event_details_id " . $id);
		}
	}

	/**
	 * save the event details to the database.
	 *
	 * @access public
	 * @return mixed
	 * @since  1.0
	 */
	public function save() {
		/*
		 * check to see if the event details alraedy exist, if they do update $this->event_details_id,
		 * if they don't create a new entry, we won't update event details because it's not directly
		 * related to any single event or user and can be spread accross multiple events/users.
		 */
		$id = $this->eventDetailsExist();

		if ($id !== false) {
			$this->event_details_id = $id;
		} else {
			$this->insert();
			$this->insert_id = $this->db->insert_id();
			$this->event_details_id = $this->eventDetailsExist();
		}

		return $this->event_details_id;
	}

	/**
	 * check to see if event details exist
	 *
	 * @access public
	 * @return mixed (boolean false if they don't exist or event_details_id (int) if they do)
	 * @since  1.0
	 */
	public function eventDetailsExist() {
		$result = false;
		$details_array = array();

		$details_array['smoking'] = $this->smoking;
		$details_array['food_available'] = $this->food_available;
		$details_array['age_range'] = $this->age_range;

		$details = $this->db->get_where('EVENT_DETAILS', $details_array);
		$details = $details->result();

		if ($details) {
			$result = $details['event_details_id'];
		}

		return $result;
	}

	/**
	 * insert a new event details row
	 *
	 * @access private
	 * @return unknown
	 * @since  1.0
	 */
	private function insert() {
		$values = array();

		$values["smoking"] = $this->smoking;
		$values["food_available"] = $this->dbNumberFormat($this->food_available);
		$values["age_range"] = $this->age_range;

		return $this->db->insert("EVENT_DETAILS", $values);
	}

	/**
	 * this is only here to appease the parent class abstract method declaration
	 * we won't update event_details because one entry can be spread accross
	 * multiple events or users, therefore we will likely need the entry at some point
	 * and instead, when an entry needs to change we will simply insert a new one.
	 */
	private function update() {

	}

	/**
	 * gets the event_details_id
	 *
	 * @return int
	 * @since  1.0
	 */
	public function getEventDetailsId() {
		return $this->event_details_id;
	}

	/**
	 * gets the smoking
	 *
	 * @return bool
	 * @since  1.0
	 */
	public function getSmoking() {
		return $this->smoking;
	}

	/**
	 * sets the smoking
	 *
	 * @param  bool
	 * @return Object
	 * @since  1.0
	 */
	public function setSmoking($smoking) {
		$this->smoking = $smoking;
		return $this;
	}

	/**
	 * gets the food_available
	 *
	 * @return bool
	 * @since  1.0
	 */
	public function getFoodAvailable() {
		return $this->food_available;
	}

	/**
	 * sets the food_available
	 *
	 * @param  bool
	 * @return Object
	 * @since  1.0
	 */
	public function setFoodAvailable($food_available) {
		$this->food_available = $food_available;
		return $this;
	}

	/**
	 * gets the age_range
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getAgeRange() {
		return $this->age_range;
	}

	/**
	 * sets the age_range
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setAgeRange($age_range) {
		$this->age_range = $age_range;
		return $this;
	}

}

?>
