<?php

/**
 * generates a single <button> field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Form_Field_Label extends Form_Field {

	private $for = null;
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
		echo form_label($this->content, $this->for, $this->filterAttributes());
	}

	/**
	 * adds the input id to the for attribute
	 *
	 * @param string $for
	 * @return \Label
	 */
	public function setFor($for) {
		$this->for = $for;
		return $this;
	}

	/**
	 * sets the label content <label>content</label>
	 *
	 * @param string $content
	 * @return \Label
	 */
	public function setContent($content) {
		$this->content = $content;
		return $this;
	}

}

?>
