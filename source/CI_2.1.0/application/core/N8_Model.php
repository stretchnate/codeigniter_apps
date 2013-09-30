<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class N8_Model extends CI_Model {

	protected $CI;

	public function __construct(&$CI) {
		$this->CI = $CI;
	}

	function transactionStart() {
		$this->CI->db->trans_start();
	}

	function transactionEnd() {
		$this->CI->db->trans_complete();
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

