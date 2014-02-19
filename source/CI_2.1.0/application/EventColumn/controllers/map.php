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

			$this->load->view('Map');
			$this->view = new MapVW();
			$this->view->setPageId('map');
			$this->view->setMiniSearch(new Plugins_MiniSearch());
		}

		/**
		 * the main map view
		 *
		 * @todo make this automatically search on the users zip if logged in.
		 * @return void
		 */
		public function index() {
			//load the map view
			$this->view->renderView();
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

			if(!empty($data['zip'])) {
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
				$iterator = new EventIterator($event_id, $event_name, $city, $state, $zip, $start_date, $end_date);
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
		public function preview($event_id) {
			$data = array('event_id' => $event_id);
			$this->renderView($data);
		}
	}

?>
