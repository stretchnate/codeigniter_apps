<?php
	if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

	/**
	 * Description of search
	 *
	 * @author stretch
	 */
	class map extends N8_Controller {

		public function __construct() {
			parent::__construct();

			$this->initializeView('all_events');
		}

		protected function initializeView($type) {
			switch($type) {
				case 'single_event':
					$this->load->view('SingleEvent');
					$this->view = new SingleEventVW();
					break;

				case 'all_events':
				default:
					$this->load->view('Map');
					$this->view = new MapVW();
//					$this->generateCategoriesNav();
			}

			$this->view->setPageId('map');
			$this->view->setMiniSearch(new Plugins_MiniSearch());
		}

		/**
		 * the main map view
		 *
		 * @return void
		 */
		public function index() {
			//load the map view
			$location_array = $this->session->userdata('location');
			if(!empty($location_array)) {
				$data = $location_array;
			} else {
				$data['zip'] = $this->session->userdata('zip');
			}

			$this->renderView($data);
		}

		/**
		 * method used to search for events
		 *
		 * @return void
		 */
		public function search() {
			$search_type = $this->input->post('search_type');

			if($this->validate($search_type)) {
				$this->renderView($this->input->post());
			} else {
				if($search_type == 'advanced_search') {
					$errors = array();
					$errors['event_title'] = array(
													'value' => $this->input->post('event_title'),
													'error' => form_error('event_title')
												);

					$errors['city']        = array(
													'value' => $this->input->post('city'),
													'error' => form_error('city')
												);

					$errors['state']       = array (
													'value' => $this->input->post('state'),
													'error' => form_error('state')
												);

					$errors['zip']         = array(
													'value' => $this->input->post('zip'),
													'error' => form_error('zip')
												);

					$errors['start_date']  = array(
													'value' => $this->input->post('start_date'),
													'error' => form_error('start_date')
												);

					$errors['end_date']  = array(
													'value' => $this->input->post('end_date'),
													'error' => form_error('end_date')
												);

					$cache_key = CacheUtil::generateCacheKey('error_');

					$cache_util = new CacheUtil();
					$cache_util->saveCache($cache_key, $errors);

					redirect('/search/advanced/'.$cache_key);
				} else {
					$this->index();
				}
			}
		}

		/**
		 * displays the map view
		 *
		 * @param array $data
		 * @return void
		 * @since 1.0
		 */
		protected function renderView($data) {
			$event_name = null;
			$event_id = null;
			$city = null;
			$state = null;
			$zip = null;
			$category_id = null;
			$start_date = null;
			$end_date   = null;

			if(!empty($data['event_title'])) {
				$event_name = $data['event_title'];
			}

			if(!empty($data['city'])) {
				$city = $data['city'];
			}

			if(!empty($data['state'])) {
				$state = $data['state'];
			}

			if(!empty($data['mini_search_zip'])) {
				$zip = $data['mini_search_zip'];
			} else if(!empty($data['zip'])) {
				$zip = $data['zip'];
			}

			if(!empty($data['event_id'])) {
				$event_id = $data['event_id'];
			}

			if(!empty($data['start_date'])) {
				$start_date = $data['start_date'];
			}

			if(!empty($data['end_date'])) {
				$end_date = $data['end_date'];
			}

			try {
				$iterator = new EventIterator($event_id, $event_name, $city, $state, $zip, $category_id, $start_date, $end_date);
				$this->view->setEventIterator($iterator);
			} catch(Exception $e) {
				$this->setMessage($e->getMessage());
			}

			$this->view->setErrors($this->getErrors());
			$this->view->renderView();
		}

		/**
		 * this method shows a preview of an event when it is added to the system
		 *
		 * @param int $event_id
		 */
		public function event_details($event_id, $show_edit_link = false) {
			$this->initializeView('single_event');
            $this->view->setUserId($this->session->userdata('user_id'));
			$data = array('event_id' => EventMask::unmaskEventId($event_id));
			$this->renderView($data);
		}

		/**
		 * show events by category (location based)
		 *
		 * @param  int  $category_id
		 * @return void
		 * @since  1.0
		 */
		public function bycategory($category_id) {
			$location_array = $this->session->userdata('location');
			$data = array('category_id' => $category_id);
			if(!empty($location_array)) {
				$data = array(
							'city' => $location_array['city'],
							'state' => $location_array['state']
						);
			} else {
				//fall back on user defined zip
				$data['zip'] = $this->session->userdata('zip');
			}

			$this->renderView($data);
		}
	}

?>
