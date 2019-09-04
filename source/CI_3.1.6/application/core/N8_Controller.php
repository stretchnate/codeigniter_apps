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

    /**
     * send a JSON response to an api request
     *
     * @param     $data
     * @param int $http_code
     */
	protected function JSONResponse($data, $http_code = 200) {
	    http_response_code($http_code);

	    echo json_encode($data);
    }

    /**
     * Pretty print json/array data
     *
     * @param $data
     */
    protected function prettyPrint($data) {
        echo "<pre>".print_r($data, true)."</pre>";
    }
}
