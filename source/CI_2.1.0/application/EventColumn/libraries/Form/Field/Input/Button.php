<?php

/**
 * generates a single <button> field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Form_Field_Input_Button extends Form_Field_Input implements Form_Field_Interface {

	private $content = null;

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
		$this->renderField(form_button($this->filterAttributes(), $this->content, $this->element_javascript));
	}

	/**
	 * adds the button content <button>content</button>
	 *
	 * @param type $content
	 * @return \Field_Input_Button
	 */
	public function setContent($content) {
		$this->content = $content;
		return $this;
	}

	/**
	 * This is an alias for setContent
	 *
	 * @param mixed $value
	 * @return \Form_Field_Input_Button
	 */
	public function setValue($value) {
		$this->setContent($value);
		return $this;
	}
}

?>
