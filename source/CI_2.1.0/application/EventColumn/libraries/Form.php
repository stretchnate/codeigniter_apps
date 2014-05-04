<?php

//if (!defined('BASEPATH'))
//	exit('No direct script access allowed');

/**
 * builds a form using the CI form_helper functions
 *
 * @author Stretch <stretchnate@gmail.com>
 * @since 1.0
 */
class Form {

	const FORM_ENCTYPE_APPLICATION = 'application/x-www-form-urlencoded';
	const FORM_ENCTYPE_MULTIPART = 'multipart/form-data';
	const FORM_ENCTYPE_PLAIN = 'text/plain';
	const FORM_TARGET_SELF = '_self';
	const FORM_TARGET_BLANK = '_blank';
	const FORM_TARGET_PARENT = '_parent';
	const FORM_TARGET_TOP = '_top';

	private $fields = array();
	private $action;
	private $enctype;
	private $hidden_inputs = array();
	private $attributes = array(
	    'autocomplete' => null,
	    'method' => 'post',
	    'name' => null,
		'id' => null,
	    'novalidate' => null,
	    'target' => null
	);

	function __construct($action = '', $method = 'post', $name = null, $id = null, $novalidate = null, $target = null, $autocomplete = null, $enctype = null) {
		try {
			$this->setAction($action);
			$this->setMethod($method);
			$this->setName($name);
			$this->setId($id);
			$this->setNovalidate($novalidate);
			$this->setAutocomplete($autocomplete);

			if(!is_null($target)) {
				$this->setTarget($target);
			}

			if(!is_null($enctype)) {
				$this->setEnctype($enctype);
			}
		} catch(Exception $ex) {
			Utilities::show500('Oops! there was a problem loading this page', $ex);
		}
	}

	/**
	 * renders the form
	 *
	 * @return void
	 * @access public
	 * @since 1.0
	 * @throws Exception
	 */
	public function renderForm() {
		try {
			echo "<div id='".$this->getContainerId()."'>";
			if ($this->enctype == self::FORM_ENCTYPE_MULTIPART) {
				echo form_open_multipart($this->action, $this->filterAttributes(), $this->hidden_inputs);
			} else {
				echo form_open($this->action, $this->filterAttributes(), $this->hidden_inputs);
			}

			//render the form fields in the order they arrived.
			foreach ($this->fields as $field) {
				$field->generateField();
			}

			echo form_close();
			echo "</div>";
		} catch (Exception $e) {
			throw new Exception("there was an eror rendering the form: " . $e->getMessage());
		}
	}

	/**
	 * fetches the form container id
	 *
	 * @return string
	 */
	private function getContainerId() {
		$form_id = "form";

		if(!is_null($this->attributes['id'])) {
			$form_id = $this->attributes['id'];
		} else if(!is_null($this->attributes['name'])) {
			$form_id = $this->attributes['name'];
		}

		$form_id .= "_container";

		return $form_id;
	}

	/**
	 * filters through $this->attribtues returning only attributes that are !is_null()
	 *
	 * @return array
	 * @since 1.0
	 */
	protected function filterAttributes() {
		$attributes = array();
		foreach ($this->attributes as $attribute => $value) {
			if ($value !== null) {
				$attributes[$attribute] = $value;
			}
		}

		return $attributes;
	}

	/**
	 * adds a field to the form
	 *
	 * @param Form_field_Interface $field
	 */
	public function addField(Form_Field_Interface $field) {
		$this->fields[] = $field;
	}

	/**
	 * returns the form field object for the type specified.
	 *
	 * @param type $type
	 * @return Object
	 * @since 1.0
	 * @throws UnexpectedValueException
	 */
	public static function getNewField($type) {
		switch ($type) {
			case Form_Field::FIELD_TYPE_HIDDEN:
				$field = new Form_Field_Hidden();
				break;

			case Form_Field::FIELD_TYPE_INPUT:
			case Form_Field::FIELD_TYPE_TEXT:
				$field = new Form_Field_Input();
				break;

			case Form_Field::FIELD_TYPE_PASSWORD:
				$field = new Form_Field_Input_Password();
				break;

			case Form_Field::FIELD_TYPE_UPLOAD:
			case Form_Field::FIELD_TYPE_FILE:
				$field = new Form_Field_Input_File();
				break;

			case Form_Field::FIELD_TYPE_TEXTAREA:
				$field = new Form_Field_Input_Textarea();
				break;

			case Form_Field::FIELD_TYPE_DROPDOWN:
			case Form_Field::FIELD_TYPE_SELECT:
				$field = new Form_Field_Select();
				break;

			case Form_Field::FIELD_TYPE_MULTISELECT:
				$field = new Form_Field_Select_MultiSelect();
				break;

			case Form_Field::FIELD_TYPE_FIELDSET_OPEN:
				$field = new Form_Field_Fieldset();
				break;

			case Form_Field::FIELD_TYPE_FIELDSET_CLOSE;
				$field = new Form_Field_FieldsetClose();
				break;

			case Form_Field::FIELD_TYPE_CHECKBOX:
				$field = new Form_Field_Input_Checkbox();
				break;

			case Form_Field::FIELD_TYPE_RADIO:
				$field = new Form_Field_Input_Checkbox_Radio();
				break;

			case Form_Field::FIELD_TYPE_SUBMIT:
				$field = new Form_Field_Input_Submit();
				break;

			case Form_Field::FIELD_TYPE_LABEL:
				$field = new Form_Field_Label();
				break;

			case Form_Field::FIELD_TYPE_RESET:
				$field = new Form_Field_Input_Reset();
				break;

			case Form_Field::FIELD_TYPE_BUTTON:
				$field = new Form_Field_Input_Button();
				break;

			case Form_Field::FIELD_TYPE_RECAPTCHA:
				$field = new Form_Field_Recaptcha();
				break;

			default:
				throw new UnexpectedValueException("invalid form field defined");
		}

		return $field;
	}

	public function getFields() {
		return $this->fields;
	}

	public function setAutocomplete($autocomplete) {
		$autocomplete = strtolower(trim($autocomplete));
		if ($autocomplete != 'on' && $autocomplete != 'off') {
			$autocomplete = (Utilities::getBoolean($autocomplete) !== false) ? 'on' : 'off';
		}

		$this->attributes['autocomplete'] = $autocomplete;
		return $this;
	}

	/**
	 * accepts application/x-www-form-urlencoded, multipart/form-data, text/plain
	 *
	 * @param string $enctype
	 * @throws UnexpectedValueException
	 * @return \Form
	 */
	public function setEnctype($enctype) {
		switch ($enctype) {
			case self::FORM_ENCTYPE_APPLICATION:
			case self::FORM_ENCTYPE_MULTIPART:
			case self::FORM_ENCTYPE_PLAIN:
				$this->enctype = $enctype;
				break;
			default:
				throw new UnexpectedValueException("invalid enctype specified");
		}

		return $this;
	}

	public function getEnctype() {
		return $this->enctype;
	}

	public function setMethod($method) {
		$this->attributes['method'] = strtolower($method);
		return $this;
	}

	public function setName($name) {
		$this->attributes['name'] = $name;
		return $this;
	}

	public function setId($id) {
		$this->attributes['id'] = $id;
		return $this;
	}

	public function setNovalidate($novalidate) {
		$this->attributes['novalidate'] = (Utilities::getBoolean($novalidate) === true) ? 'novalidate' : null;
		return $this;
	}

	/**
	 * accepts _self, _blank, _parent, _top
	 *
	 * @param string $target
	 * @throws UnexpectedValueException
	 * @return \Form
	 */
	public function setTarget($target) {
		switch ($target) {
			case self::FORM_TARGET_BLANK:
			case self::FORM_TARGET_PARENT:
			case self::FORM_TARGET_SELF:
			case self::FORM_TARGET_TOP:
				$this->attributes['target'] = $target;
				break;
			default:
				throw new UnexpectedValueException("invalid target specified");
		}

		return $this;
	}

	public function addHiddenInput($name, $value) {
		$this->hidden_inputs[$name] = $value;
		return $this;
	}

	public function setHiddenInputs(array $hidden_inputs) {
		$this->hidden_inputs = $hidden_inputs;
		return $this;
	}

	public function getHiddenInputs() {
		return $this->hidden_inputs;
	}

	public function setAction($action) {
		$this->action = $action;
		return $this;
	}

	public function getAction() {
		return $this->action;
	}

	public function getAttributes($attribute = null) {
		if($attribute) {
			return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : false;
		}

		return $this->attributes;
	}

}

