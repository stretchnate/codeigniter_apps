<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class N8_Model extends CI_Model {

	public function __construct() {
		parent::__construct();

		//load the CI instance and reference the db object
		$CI = & get_instance();
		$this->db = & $CI->db;
	}

	function transactionStart() {
		$this->db->trans_start();
	}

	function transactionEnd() {
		$this->db->trans_complete();
	}

	/**
	 * This method formats a number to be inserted into the database (for float columns)
	 *
	 * @param $value float
	 * @since 2012.08.18
	 */
	function dbNumberFormat($value) {
		return number_format($value, 2, '.', '');
	}

}

