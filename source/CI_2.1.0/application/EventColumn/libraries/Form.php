<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * builds a form using the CI form_helper functions
 *
 * @author Stretch <stretchnate@gmail.com>
 * @since 1.0
 */
class Form {

	private $fields = array();

	function __construct() {

	}

	public function addField($type) {
//		$field = FormField::getFormField($type);
		//add attributes and such

		$this->fields[] = $field;
	}

}

