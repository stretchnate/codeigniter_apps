<?php

/**
 * generates a single <button> field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Form_Field_Fieldset extends Form_Field {

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
