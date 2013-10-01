<?php

/**
 * generates a single field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class FormField_Hidden extends FormField {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Generates the form field
	 *
	 * @return string
	 * @access public
	 * @since 1.0
	 */
	public function generateField() {
		return form_hidden($this->name, $this->value);
	}

}

?>
