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
		 * initializes the iterator
		 *
		 * @param array $where_clause
		 * @return void
		 * @since 1.0
		 * @access public
		 */
		public function __construct($where_clause) {
			$this->ci =& get_instance();

			$this->loadEvents($where_clause);

			$this->rewind();
		}

		/**
		 * loads the event_dm_array with instances of EventModel_EventDM based on the where_clause argument
		 *
		 * @param  array $where_clause
		 * @return void
		 * @since  1.0
		 * @throws Exception
		 * @access private
		 */
		private function loadEvents($where_clause) {
			if(empty($where_clause)) {
				throw new Exception('Empty where clause not allowed');
			} else {
				$this->ci->load->database();
				$query = $this->ci->db->get_where('EVENTS', $where_clause);

				if(is_array($query->result())) {
					foreach($query->result() as $event) {
						$event_dm = new EventModel_EventDM();
						$event_dm->setEventId($event->event_id);
						$event_dm->setEventOwner($event->event_owner);
						$event_dm->setEventCategory($event->event_category);
						$event_dm->setEventDescription($event->event_description);
						$event_dm->setEventName($event->event_name);
						$event_dm->setEventStartDateTime($event->event_start_datetime);
						$event_dm->setEventEndDatetime($event->event_end_datetime);
						$event_dm->setEventImage($event->event_image);
						$event_dm->loadEventLocations();
						$event_dm->loadEventCategoryModel();
						$event_dm->loadEventOwnerModel();

						$this->event_dm_array[] = $event_dm;
					}
				}

				unset($query);
			}
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
