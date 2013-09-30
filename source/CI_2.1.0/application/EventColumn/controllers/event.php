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

	//@TODO remove this once I'm certain transactions are working.
	public function transactionTest() {
		$CI = & get_instance();
		$model = new EventModel($CI);
		$model->transactionTest();
//		$model = new TransactionTestDM();
//		$model->transactionStart();
//		$model->insert1();
//		$model->insert2();
//		$model->transactionEnd();
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
