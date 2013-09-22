<?php

/**
 * this class is the controller class for adding and updating events as well as event locations and event details
 */
class Event extends Controller {

	public function __construct() {

	}

	/**
	 * load the event add page
	 *
	 * @return void
	 * @access public
	 * @since 1.0
	 */
	public function index() {
		$this->load->view('event');

		$view = new EventVW(get_instance());

		$view->renderView();
	}

	/**
	 * add a new event
	 *
	 * @return void
	 * @access public
	 * @since 1.0
	 */
	public function addEvent() {

	}

}

?>
