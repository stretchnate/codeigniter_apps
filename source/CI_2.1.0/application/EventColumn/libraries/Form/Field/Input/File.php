<?php

/**
 * generates a single text field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Field_Input_File extends Field_Input {

	public function __construct() {
		parent::__construct();

		//add the accepts attribute to the attribtues
		$this->attributes['accepts'] = null;
	}

	/**
	 * Generates the form field
	 *
	 * @return string
	 * @access public
	 * @since 1.0
	 */
	public function generateField() {
		$this->renderField(form_upload($this->filterAttributes(), '', $this->element_javascript));
	}

	public function setAccepts($accepts) {
		$this->attributes['accepts'] = $accepts;
		return $this;
	}

}

?>
