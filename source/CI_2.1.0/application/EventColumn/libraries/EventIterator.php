<?php
	/**
	 * allows the Iteration through an array of EventModel_EventDM objects
	 *
	 * @author stretch
	 */
	class EventIterator implements Iterator {

		private $position = 0;
		private $event_dm_array = array();
		private $ci;

		/**
		 * initialize the iterator, while none of the params are "required" by the method call
		 * you must pass an event_id or (city and state) or zip.
		 *
		 * @param int    $event_id
		 * @param string $name
		 * @param string $city
		 * @param string $state
		 * @param int    $zip
		 * @param string $start_date
		 * @param string $end_date
		 * @return void
		 * @since 1.0
		 * @access public
		 * @throws Exception
		 */
		public function __construct($event_id = null, $name = null, $city = null, $state = null, $zip = null, $category_id = null, $start_date = null, $end_date = null) {
			$this->ci =& get_instance();
			$this->ci->load->database();

			if(!$event_id && !$zip && (!$city || !$state)) {
				throw new Exception(__CLASS__." ERROR: invalid search parameters passed. Must pass event_id or zip or (city and state)");
			}

			if(!$start_date) {
				$start_date = date('Y-m-d H:i:s');
			}

			if(!$end_date) {
				$end_date = date('Y-m-d H:i:s', strtotime('+1 day'));
			}

			$where = ' e.event_start_datetime <= "'.$end_date.'"';
			$where .= ' AND e.event_end_datetime >= "'.$start_date.'"';

			if($event_id) {
				$this->loadEventsById($event_id);
			} elseif($category_id && $zip) {
				$this->loadEventsByCategory($where, $category_id, $city, $state, $zip);
			} elseif(($city && $state) || $zip) {
				$this->loadEventsByLocation($where, $city, $state, $zip);
			}

			if(($city && $state) || $zip) {
				if(count($this->event_dm_array) > 0) {
					$this->filterLocations($city, $state, $zip);
				}
			}

			$this->rewind();
		}

		/**
		 * filters the locations on each event
		 *
		 * @param string $city
		 * @param string $state
		 * @param int    $zip
		 */
		private function filterLocations($city = null, $state = null, $zip = null) {
			$event_dm_count = count($this->event_dm_array);

			for($i = 0; $i < $event_dm_count; $i++) {
				$this->event_dm_array[$i]->filterLocations($city, $state, $zip);

				if(count($this->event_dm_array[$i]->getEventLocations()) < 1) {
					unset($this->event_dm_array[$i]);
				}
			}
		}

		/**
		 * loads the events by location
		 *
		 * @param string $where
		 * @param string $city
		 * @param string $state
		 * @param int    $zip
		 */
		private function loadEventsByLocation($where, $city = null, $state = null, $zip = null) {
			if($city) {
				$where .= ' AND el.location_city = "'.$city.'"';
			}

			if($state) {
				$where .= ' AND el.location_state = "'.$state.'"';
			}

			if($zip) {
				$where .= ' AND el.location_zip = "'.$zip.'"';
			}

			$query = $this->ci->db->select()
									->from('EVENT_LOCATIONS el')
									->join('EVENTS e', 'e.event_id = el.event_id')
									->where($where, null, false)
									->get();

			if(is_array($query->result())) {
				foreach($query->result() as $row) {
					$event_dm = new EventModel_EventDM();
					$event_dm->load($row->event_id);

					$this->event_dm_array[] = $event_dm;
				}
			}
		}

		/**
		 * loads the event_dm_array with instances of EventModel_EventDM based on the event_id argument
		 *
		 * @param  int  $event_id
		 * @return void
		 * @since  1.0
		 * @access private
		 */
		private function loadEventsById($event_id) {
			$where = ' event_id = '.$event_id;

			$query = $this->ci->db->select()
								->from('EVENTS e')
								->where($where, null, false)
								->get();

			if(is_array($query->result())) {
				foreach($query->result() as $event) {
					$event_dm = new EventModel_EventDM();
					$event_dm->load($event->event_id);

					$this->event_dm_array[] = $event_dm;
				}
			}

			unset($query);
		}

		/**
		 * loads the event_dm_array with instances of EventModel_EventDM based on the category_id and zip (if provided)
		 *
		 * @param  int $category_id
		 * @param  int $zip
		 * @return void
		 * @since  1.0
		 */
		private function loadEventsByCategory($where, $category_id, $city, $state, $zip) {
			$where .= ' e.event_category = '.$category_id;
			if($city && $state) {
				$where .= ' el.location_city = '.$city.' AND el.location_state = '.$state;
			} else {
				$where .= ' el.zip = '.$zip;
			}

			$query = $this->ci->db->select('e.event_id')
					->from('EVENTS e')
					->join('EVENT_LOCATIONS el', 'e.event_id = el.event_id', 'inner')
					->where($where, null, false)
					->get();

			foreach($query->result() as $row) {
				$event_dm = new EventModel_EventDM();
				$event_dm->load($row->event_id);

				$this->event_dm_array[] = $event_dm;
			}

			unset($query);
		}

		/**
		 * advances the position by 1
		 *
		 * @since 1.0
		 * @access public
		 */
		public function next() {
			++$this->position;
		}

		/**
		 * returns the current iterator item
		 *
		 * @return object EventModel_EventDM
		 * @since  1.0
		 * @access public
		 */
		public function current() {
			return $this->event_dm_array[$this->position];
		}

		/**
		 * returns the current iterator position
		 *
		 * @return int
		 * @since  1.0
		 * @access public
		 */
		public function key() {
			return $this->position;
		}

		/**
		 * determines if the iterator position points to a valid iterator item
		 *
		 * @return bool
		 * @since  1.0
		 * @access public
		 */
		public function valid() {
			return (isset($this->event_dm_array[$this->position]) && $this->event_dm_array[$this->position] instanceof EventModel_EventDM);
		}

		/**
		 * rewinds the position to 0
		 *
		 * @since  1.0
		 * @access public
		 */
		public function rewind() {
			$this->position = 0;
		}

		/**
		 * returns the event id for the current iterator item
		 *
		 * @return int
		 * @since  1.0
		 * @access public
		 */
		public function getEventId() {
			return $this->event_dm_array[$this->position]->getEventId();
		}

		/**
		 * returns the event owner id.
		 * be careful not to display this data on a web page with any indication that it is an id
		 *
		 * @return int
		 * @since  1.0
		 * @access public
		 */
		public function getEventOwnerId() {
			return $this->event_dm_array[$this->position]->getEventOwner();
		}

		/**
		 * returns the name of the event
		 *
		 * @return string
		 * @since  1.0
		 * @access public
		 */
		public function getEventName() {
			return $this->event_dm_array[$this->position]->getEventName();
		}

		/**
		 * returns the starting date and time of the event
		 *
		 * @return string
		 * @since  1.0
		 * @access public
		 */
		public function getEventStart() {
			return $this->event_dm_array[$this->position]->getEventStartDatetime();
		}

		/**
		 * returns the ending date and time of the event
		 *
		 * @return string
		 * @since  1.0
		 * @access public
		 */
		public function getEventEnd() {
			return $this->event_dm_array[$this->position]->getEventEndDatetime();
		}

		/**
		 * returns the description of the event
		 *
		 * @return string
		 * @since  1.0
		 * @access public
		 */
		public function getEventDescription() {
			return $this->event_dm_array[$this->position]->getEventDescription();
		}

		/**
		 * returns the category name of the event
		 *
		 * @return string
		 * @since  1.0
		 * @access public
		 */
		public function getEventCategory() {
			return $this->event_dm_array[$this->position]->getEventCategoryDM()->getCategoryName();
		}

		/**
		 * returns the locations of the event
		 *
		 * @return array
		 * @since  1.0
		 * @access public
		 */
		public function getEventLocations() {
			return $this->event_dm_array[$this->position]->getEventLocations();
		}
	}

?>
