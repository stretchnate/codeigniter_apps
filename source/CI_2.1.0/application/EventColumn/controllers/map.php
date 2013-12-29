<?php
	/**
	 * Description of search
	 *
	 * @author stretch
	 */
	class map extends N8_Controller {

		public function index() {
			//load the map view
			$this->load->view('Map');

			$view = new MapVW();
			$view->setPageId('map');
			$view->renderView();
		}

		public function search() {
			$search_type = $this->input->post('search_type');

			if($this->validate($search_type)) {
				$this->renderView($this->input->post());
			} else {
				if($search_type == 'advanced_search') {
					$this->load->driver('cache', array('adapter' => 'apc'));

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

					$cache_key = Utilities::generateCacheKey('error_');

					$this->cache->save($cache_key, $errors, 600);

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
			//load the map view
			$this->load->view('Map');

			$where_clause = array();
			$iterator     = null;

			if(!empty($data['event_title'])) {
				$where_clause['event_title'] = $data['event_title'];
			}

			if(!empty($data['city'])) {
				$where_clause['city'] = $data['city'];
			}

			if(!empty($data['state'])) {
				$where_clause['state'] = $data['state'];
			}

			if(!empty($data['zip'])) {
				$where_clause['zip'] = $data['zip'];
			}

			if(!empty($where_clause)) {
				$iterator = new EventIterator($where_clause);
			}

			$view = new MapVW('map');
			$view->setEventIterator($iterator);
			$view->setPageId('map');
			$view->renderView();
		}
	}

?>
