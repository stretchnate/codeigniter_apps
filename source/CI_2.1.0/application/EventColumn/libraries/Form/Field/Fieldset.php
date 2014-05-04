<?php

/**
 * generates a single <fieldset> tag for the Form object using the CI form_helper functions
 * should be used in conjuction with the Form_Field_FieldsetClose class
 *
 * @author stretch
 */
class Form_Field_Fieldset extends Form_Field implements Form_Field_Interface {

	private $legend;

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
		echo form_fieldset($this->legend, $this->filterAttributes());
	}

	/**
	 * sets the <legend> content of the fieldset
	 *
	 * @param string $legend
	 * @return \Field_Fieldset
	 */
	public function setLegend($legend) {
		$this->legend = $legend;
		return $this;
	}

}

?>
