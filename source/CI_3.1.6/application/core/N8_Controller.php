<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class N8_Controller extends CI_Controller {

    protected $view;

	function __construct() {
		parent::__construct();
	}

	/**
	 * form validation method
	 */
	function validate(&$rules) {
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules($rules);

		return $this->form_validation->run();
	}
}
