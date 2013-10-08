<?php

/**
 * generates a single <button> field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Form_Field_FieldsetClose {

	private $trailing_html = null;

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
		echo form_fieldset_close($this->trailing_html);
	}

	/**
	 * The only advantage to using this function is it permits you to pass data to it which will be added below the </field> tag.
	 *
	 * @param string $trailing_html
	 * @return \Field_FieldsetClose
	 */
	public function setTrailingHtml($trailing_html) {
		$this->trailing_html = $trailing_html;
		return $this;
	}

}

?>
