<?php

/**
 * generates a single field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Field_Hidden extends Field {

	private $hidden_elements_array = array();

	public function __construct() {
		parent::__construct();
		$this->attributes['value'] = null;
	}

	/**
	 * Generates the form field
	 *
	 * @return string
	 * @access public
	 * @since 1.0
	 */
	public function generateField() {
		if (!empty($this->hidden_elements_array)) {
			echo form_hidden($this->hidden_elements_array);
		} else {
			echo form_hidden($this->attributes['name'], $this->attributes['value']);
		}
	}

	public function setHiddenElementsArray(array $hidden_elements_array) {
		$this->hidden_elements_array = $hidden_elements_array;
		return $this;
	}

	public function setValue($value) {
		$this->attributes['value'] = $value;
		return $this;
	}

}

?>
