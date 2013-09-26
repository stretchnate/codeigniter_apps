<?php

/**
 * this class is the controller class for adding and updating events as well as event locations and event details
 */
class Event extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * load the event add page
	 *
	 * @return void
	 * @access public
	 * @since 1.0
	 */
	public function index() {
		$this->load->view('Event');

		$view = new EventVW();

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
