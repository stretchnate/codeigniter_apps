<?php

class Blackjack extends N8_Controller {

	function Blackjack() {
		parent::__construct();
	}

	function index() {
		$this->load->view("blackjack/blackjack.php");
	}
}
?>