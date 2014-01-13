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
		private $event_locations = array( );
		private $event_category_dm;
		private $event_owner_dm;

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
		public function load( $id ) {
			$query = $this->db->get_where( "EVENTS", array( "event_id" => $id ) );

			if( $query->num_rows > 0 ) {
				$event = $query->row();

				$this->event_id				 = $event->event_id;
				$this->event_owner			 = $event->event_owner;
				$this->event_name			 = $event->event_name;
				$this->event_start_datetime	 = $event->event_start_datetime;
				$this->event_end_datetime	 = $event->event_end_datetime;
				$this->event_description	 = $event->event_description;
				$this->event_category		 = $event->event_category;
				$this->event_image			 = $event->event_image;

				$this->loadEventLocations();
				$this->loadEventCategoryModel();
				$this->loadEventOwnerModel();
			} else {
				throw new Exception( "unable to load event [" . $this->event_id . "]" );
			}
		}

		/**
		 * loads the UserProfile for  the event owner/sponsor
		 *
		 * @return void
		 * @since 1.0
		 */
		public function loadEventOwnerModel() {
			$this->event_owner_dm = new UserProfileDM();
			$this->event_owner_dm->load($this->event_owner);
		}

		/**
		 * loads the event category model for the event
		 *
		 * @return void
		 * @since 1.0
		 */
		public function loadEventCategoryModel() {
			$this->event_category_dm = new EventModel_EventCategoriesDM();
			$this->event_category_dm->load($this->event_category);
		}

		/**
		 * loads the event locatoins for the event.
		 *
		 * @access public
		 * @return void
		 * @since  1.0
		 */
		public function loadEventLocations() {
			$query = $this->db->get_where( "EVENT_LOCATIONS", array( "event_id" => $this->event_id ) );

			foreach( $query->result() as $row ) {
				$location = new EventModel_EventLocationDM();
				$location->setLocationId( $row->location_id )
						->setEventLocation( $row->event_location )
						->setLatLong( $row->lat_long )
						->setLocationAddress( $row->location_address )
						->setLocationCity( $row->location_city )
						->setLocationState( $row->location_state )
						->setLocationZip( $row->location_zip )
						->setLocationCountry( $row->location_country )
						->setEventCost( $row->event_cost )
						->setEventId( $row->event_id )
						->setEventDetailsId( $row->event_details_id );

				$location->loadEventDetailsDM();

				$this->addEventLocation( $location );
			}
		}

		/**
		 * Filters the locations by city and state or zip for this event
		 *
		 * @param string $city
		 * @param string $state
		 * @param int $zip
		 * @return void
		 * @since 1.0
		 */
		public function filterLocations($city = null, $state = null, $zip = null) {
			if($city && $state) {
				$this->filterLocationsByCityState(strtoupper($city), strtoupper($state));
			}

			if($zip) {
				$this->filterLocationsByZip($zip);
			}
		}

		/**
		 * Filters locations by city and state
		 *
		 * @param string $city
		 * @param string $state
		 * @return void
		 * @since 1.0
		 */
		private function filterLocationsByCityState($city, $state) {
			if(is_array($this->event_locations)) {
				foreach($this->event_locations as $location) {
					if(strcasecmp($location->getLocationCity(), $city) || strcasecmp($location->getLocationState(), $state)) {
						unset($location);
					}
				}
			}
		}

		/**
		 * filters locations by zip
		 *
		 * @param int $zip
		 * @return void
		 * @since 1.0
		 */
		private function filterLocationsByZip($zip) {
			if(is_array($this->event_locations)) {
				foreach($this->event_locations as $location) {
					if($location->getLocationZip() != $zip) {
						unset($location);
					}
				}
			}
		}

		/**
		 * save the event to the database.
		 *
		 * @access public
		 * @return mixed
		 * @since  1.0
		 * @throws Exception
		 */
		public function save() {
			if( $this->event_id > 0 ) {
				$result = $this->update();
			} else {
				$result = $this->insert();
			}

			if($result === false) {
				throw new Exception("There was a problem updating the event [" . $this->event_id . "]");
			} else {
				$this->loadEventId();
				return $result;
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
			$sets							 = array( );
			$sets['event_owner']			 = $this->event_owner;
			$sets['event_name']				 = $this->event_name;
			$sets['event_start_datetime']	 = $this->event_start_datetime;
			$sets['event_end_datetime']		 = $this->event_end_datetime;
			$sets['event_description']		 = $this->event_description;
			$sets['event_category']			 = $this->event_category;
			$sets['event_image']			 = $this->event_image;

			$this->db->where( "event_id", $this->event_id );
			return $this->db->update( "EVENTS", $sets );
		}

		/**
		 * insert a new EVENT_CATEGORIES row
		 *
		 * @access private
		 * @return unknown
		 * @since  1.0
		 */
		protected function insert() {
			$values = array( );

			$values['event_owner']			 = $this->event_owner;
			$values['event_name']			 = $this->event_name;
			$values['event_start_datetime']	 = $this->event_start_datetime;
			$values['event_end_datetime']	 = $this->event_end_datetime;
			$values['event_description']	 = $this->event_description;
			$values['event_category']		 = $this->event_category;
			$values['event_image']			 = $this->event_image;

			return $this->db->insert( "EVENTS", $values );
		}

		/**
		 * loads the event id from the recently saved event
		 *
		 * @throws Exception
		 */
		protected function loadEventId() {
			$where = array(
				'event_owner' => $this->event_owner,
				'event_name' => $this->event_name,
				'event_category' => $this->event_category
//				'event_start_datetime' => $this->event_start_datetime,
//				'event_end_datetime' => $this->event_end_datetime
				);

			$query = $this->db->select('event_id')
								->from('EVENTS')
								->where($where)
								->get();

			if($query->num_rows < 1) {
				throw new Exception("there was an problem saving your event");
			} else {
				$this->event_id = $query->row()->event_id;
			}

		}

		/**
		 * returns the location based on the id provided
		 *
		 * @param type $location_id
		 * @return Object EventModel_EventLocationDM
		 * @access public
		 * @since 1.0
		 */
		public function getLocationById( $location_id ) {
			foreach( $this->event_locations as $location ) {
				if( $location->getLocationId() == $location_id ) {
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
		 * sets the event id
		 * @param int $event_id
		 * @return object \EventModel_EventDM
		 */
		public function setEventId($event_id) {
			$this->event_id = $event_id;
			return $this;
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
		public function setEventOwner( $event_owner ) {
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
		public function setEventName( $event_name ) {
			$this->event_name = strtoupper($event_name);
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
		public function setEventStartDateTime( $event_start_datetime ) {
			$this->event_start_datetime = Utilities::formatDate($event_start_datetime);
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
		public function setEventEndDatetime( $event_end_datetime ) {
			$this->event_end_datetime = Utilities::formatDate($event_end_datetime);
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
		public function setEventDescription( $event_description ) {
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
		public function setEventCategory( $event_category ) {
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
		public function setEventImage( $event_image ) {
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
		public function setEventLocations( array $event_locations ) {
			$this->event_locations = $event_locations;
			return $this;
		}

		/**
		 * sets the event_locations
		 *
		 * @param  Object EventModel_EventLocationDM
		 * @return Object
		 * @since  1.0
		 */
		public function addEventLocation( EventModel_EventLocationDM $event_location ) {
			$this->event_locations[] = $event_location;
			return $this;
		}

		/**
		 * returns the UserProfileDM for the event owner/sponsor
		 *
		 * @return object (UserProfileDM)
		 * @since 1.0
		 */
		public function getEventOwnerDM() {
			return $this->event_owner_dm;
		}

		/**
		 * returns the EventCategoryDM for the event
		 *
		 * @return object (EventModel_EventCategoriesDM)
		 * @since 1.0
		 */
		public function getEventCategoryDM() {
			return $this->event_category_dm;
		}
	}

?>
