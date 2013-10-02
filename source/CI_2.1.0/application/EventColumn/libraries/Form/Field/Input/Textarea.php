<?php

/**
 * generates a single text field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Field_Input_Textarea extends Field_Input {

	public function __construct() {
		parent::__construct();

		//modify attributes array to fit textarea better
		$this->attributes['rows'] = '20';
		$this->attributes['cols'] = '35';
	}

	/**
	 * Generates the form field
	 *
	 * @return string
	 * @access public
	 * @since 1.0
	 */
	public function generateField() {
		$this->renderField(form_textarea($this->filterAttributes(), '', $this->element_javascript));
	}

	public function setRows($rows) {
		$this->attribtues['rows'] = $rows;
		return $this;
	}

	public function setCols($cols) {
		$this->attributes['cols'] = $cols;
		return $this;
	}

}

?>
