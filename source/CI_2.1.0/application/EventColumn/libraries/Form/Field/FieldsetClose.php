<?php

/**
 * generates a single closing </fieldset> tag should be used in conjunction with the Form_Field_Fieldset class
 *
 * @author stretch
 */
class Form_Field_FieldsetClose extends Form_field implements Form_Field_Interface {

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
