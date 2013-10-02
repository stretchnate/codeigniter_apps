<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * builds a form using the CI form_helper functions
 *
 * @author Stretch <stretchnate@gmail.com>
 * @since 1.0
 */
class Form {

	private $fields = array();

	function __construct() {

	}

	public function addField($field) {
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
			case Field::FIELD_TYPE_HIDDEN:
				$field = new FormField_Hidden();
				break;

			case Field::FIELD_TYPE_INPUT:
			case Field::FIELD_TYPE_TEXT:
				$field = new FormField_Input();
				break;

			case Field::FIELD_TYPE_PASSWORD:
				$field = new FormField_Input_Password();
				break;

			case Field::FIELD_TYPE_UPLOAD:
			case Field::FIELD_TYPE_FILE:
				$field = new FormField_Input_File();
				break;

			case Field::FIELD_TYPE_TEXTAREA:
				$field = new FormField_Input_Textarea();
				break;

			case Field::FIELD_TYPE_DROPDOWN:
			case Field::FIELD_TYPE_SELECT:
				$field = new FormField_Select();
				break;

			case Field::FIELD_TYPE_MULTISELECT:
				$field = new FormField_Select_MultiSelect();
				break;

			case Field::FIELD_TYPE_FIELDSET:

				break;

			case Field::FIELD_TYPE_CHECKBOX:
				$field = new FormField_Input_Checkbox();
				break;

			case Field::FIELD_TYPE_RADIO:
				$field = new FormField_Input_Checkbox_Radio();
				break;

			case Field::FIELD_TYPE_SUBMIT:

				break;

			case Field::FIELD_TYPE_LABEL:

				break;

			case Field::FIELD_TYPE_RESET:

				break;

			case Field::FIELD_TYPE_BUTTON:

				break;

			default:
				throw new UnexpectedValueException("invalid form field defined");
		}

		return $field;
	}

}

