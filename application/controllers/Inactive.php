<?php

class Inactive extends N8_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {//@TODO redirect this to login if site is active
		$active = $this->auth->isSiteActive();

		if(!$active) {
			$this->load->view("inactive/maintenance");
		} else {
			redirect("/");
		}
	}
}
?>
