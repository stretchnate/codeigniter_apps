<?php

/**
 * generates a single field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class FormField_Input {

	//element attributes
	private $attributes = array(
	    'autocomplete' => null,
	    'autofocus' => null,
	    'disabled' => null,
	    'form' => null,
	    'form_no_validate' => null,
	    'datalist' => null,
	    'max' => null,
	    'maxlength' => null,
	    'min' => null,
	    'multiple' => null,
	    'name' => null,
	    'pattern' => null,
	    'placeholder' => null,
	    'readonly' => null,
	    'required' => null,
	    'size' => null,
	    'step' => null,
	    'value' => null
	);
	private $element_javascript = null;

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
		$attributes = array();
		foreach ($this->attributes as $attribute => $value) {
			if ($value !== null) {
				$attributes[$attribute] = $value;
			}
		}

		return form_input($attributes, '', $this->element_javascript);
	}

	//setters
	public function setAutocomplete($autocomplete) {
		$this->attributes['autocomplete'] = ($autocomplete) ? 'autocomplete' : null;
	}

	public function setAutofocus($autofocus) {
		$this->attributes['autofocus'] = ($autofocus) ? 'autofocus' : null;
	}

	public function setDisabled($disabled) {
		$this->attributes['disabled'] = ($disabled) ? 'disabled' : null;
	}

	public function setForm($form) {
		$this->attributes['form'] = $form;
	}

	public function setFormNoValidate($form_no_validate) {
		$this->attributes['form_no_validate'] = $form_no_validate;
	}

	/**
	 * refers to a datalist element that contains pre-defined options for an input element
	 * @param type $datalist
	 */
	public function setDataList($datalist) {
		$this->attributes['datalist'] = $datalist;
	}

	public function setMax($max) {
		$this->attributes['max'] = $max;
	}

	public function setMaxLength($maxlength) {
		$this->attributes['maxlength'] = $maxlength;
	}

	public function setMin($min) {
		$this->attributes['min'] = $min;
	}

	public function setMultiple($multiple) {
		$this->attributes['multiple'] = $multiple;
	}

	public function setName($name) {
		$this->attributes['name'] = $name;
	}

	public function setPatter($pattern) {
		$this->attributes['pattern'] = $pattern;
	}

	public function setPlaceholder($placeholder) {
		$this->attributes['placeholder'] = $placeholder;
	}

	public function setReadonly($readonly) {
		$this->attributes['readonly'] = ($readonly) ? 'readonly' : null;
	}

	public function setRequired($required) {
		$this->attributes['required'] = ($required) ? 'required' : null;
	}

	public function setSize($size) {
		$this->attributes['size'] = $size;
	}

	public function setStep($step) {
		$this->attributes['step'] = $step;
	}

	public function setValue($value) {
		$this->attributes['value'] = $value;
	}

	public function setElementJavascript($js) {
		$this->element_javascript = $js;
	}

}

?>
