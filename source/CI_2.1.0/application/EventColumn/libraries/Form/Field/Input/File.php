<?php

/**
 * generates a single <input type="file"> field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Form_Field_Input_File extends Form_Field_Input implements Form_Field_Interface {

	const ACCEPT_TYPE_IMAGE = 'image/*';
	const ACCEPT_TYPE_VIDEO = 'video/*';
	const ACCEPT_TYPE_AUDIO = 'audio/*';

	public function __construct() {
		parent::__construct();

		//add the accepts attribute to the attribtues
		$this->attributes['accept'] = null;
	}

	/**
	 * Generates the form field
	 *
	 * @return string
	 * @access public
	 * @since 1.0
	 */
	public function generateField() {
		$this->renderField(form_upload($this->filterAttributes(), '', $this->element_javascript));
	}

	public function setAccept($accepts) {
		$this->attributes['accept'] = $accepts;
		return $this;
	}

}

?>
