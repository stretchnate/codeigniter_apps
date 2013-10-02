<?php

/**
 * generates a single text field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Field_Select extends Field {

	protected $options = array();
	protected $selected_option;

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
		$name = $this->getSelectName();

		$attributes = $this->stringifyAttributes();

		$this->renderField(form_select($name, $this->options, $this->selected_option, $attributes));
	}

	public function addOption($option) {
		$this->options[] = $option;
		return $this;
	}

	/**
	 * converts $this->attributes to a string
	 *
	 * @return string
	 */
	protected function stringifyAttributes() {
		$attributes = '';
		foreach ($this->filterAttributes() as $attribute => $value) {
			$attributes .= " " . $attribute . "='" . $value . "'";
		}
		$attributes .= isset($this->element_javascript) ? " " . $this->element_javascript : '';

		return $attributes;
	}

	/**
	 * returns the field name and unsets it from $this->attributes
	 * @return string
	 */
	protected function getSelectName() {
		$name = $this->attributes['name'];
		unset($this->attributes['name']);

		return $name;
	}

	/**
	 * set options all at once as a single or multidimensional associative array of options.
	 * $key = value and $value = text. if multi, optgroup will be created for each dimension.
	 *
	 * @param array $options
	 * @return \FormField_Select
	 */
	public function setOptions($options) {
		$this->options = $options;
		return $this;
	}

	/**
	 * set the selected option
	 *
	 * @param string $selected_option
	 * @return \FormField_Select
	 */
	public function setSelectedOption($selected_option) {
		$this->selected_option = $selected_option;
		return $this;
	}

}

?>
