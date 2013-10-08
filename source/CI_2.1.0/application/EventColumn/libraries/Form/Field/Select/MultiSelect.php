<?php

/**
 * generates a single <select multiselect="multiselect"> field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Form_Field_Select_MultiSelect extends Form_Field_Select {

	protected $selected_option = array();

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

		/*
		 * even though form_select will create a multiselect if the third argument
		 * is an array of multiple items we still need to do this here in case the
		 * third option is empty and we still want a multiselect
		 */
		$this->renderField(form_multiselect($name, $this->options, $this->selected_option, $attributes));
	}

	/**
	 * set the selected option(s)
	 *
	 * @param array $selected_option
	 * @return \FormField_Select
	 */
	public function setSelectedOption(array $selected_option) {
		$this->selected_option = $selected_option;
		return $this;
	}

	public function addSelectedOption($selected_option) {
		$this->selected_option[] = $selected_option;
		return $this;
	}

}

?>
