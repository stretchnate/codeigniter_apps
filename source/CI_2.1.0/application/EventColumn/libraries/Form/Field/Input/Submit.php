<?php

/**
 * generates a single <input type="submit"> field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Form_Field_Input_Submit extends Form_Field_Input implements Form_Field_Interface {

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
		$this->renderField(form_submit($this->filterAttributes(), '', $this->element_javascript));
	}

}

?>
