<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class N8_Model extends CI_Model {

	protected $content_db;

	public function __construct($alternate_db = null) {
		parent::__construct();

		if ($alternate_db) {
			$this->content_db = $this->load->database($alternate_db, true, true);
		}
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

