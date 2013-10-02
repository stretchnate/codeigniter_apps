<?php

/**
 * generates a single text field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Field_Input_Password extends Field_Input {

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
		$this->renderField(form_password($this->filterAttributes(), '', $this->element_javascript));
	}

}

?>
