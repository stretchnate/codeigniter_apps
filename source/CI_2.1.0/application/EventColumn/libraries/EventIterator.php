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
		 * initialize the iterator
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
		public function __construct($event_id = null, $name = null, $city = null, $state = null, $zip = null, $start_date = null, $end_date = null) {
			$this->ci =& get_instance();
			$this->ci->load->database();

			if(($city && !$state) || (!$city && $state)) {
				throw new Exception(__CLASS__." ERROR: city without state or state without city not allowed");
			}

			if(!$start_date) {
				$start_date = date('Y-m-d H:i:s');
			}

			if(!$end_date) {
				$end_date = date('Y-m-d H:i:s', strtotime('+1 day'));
			}

			$where = ' e.event_start_datetime <= "'.$end_date.'"';
			$where .= ' AND e.event_end_datetime >= "'.$start_date.'"';

			if(($city && $state) || $zip) {
				$this->loadEventsByLocation($where, $city, $state, $zip);
			} elseif($name || $event_id) {
				$this->loadEvents($where, $name, $event_id);
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
		 * loads the event_dm_array with instances of EventModel_EventDM based on the where_clause argument
		 *
		 * @param  string $where
		 * @return void
		 * @since  1.0
		 * @access private
		 */
		private function loadEvents($where, $name = null, $event_id = null) {
			if($name) {
				$where .= ' AND event_name LIKE "%'.$name.'%"';
			}

			if($event_id) {
				//search only by id if it is provided
				$where = ' event_id = '.$event_id;
			}

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
