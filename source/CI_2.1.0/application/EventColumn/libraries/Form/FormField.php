<?php

/**
 * parent class of the formField objects
 *
 * @author stretch
 */
abstract class FormField {

	const FIELD_TYPE_HIDDEN = 'hidden';
	const FIELD_TYPE_INPUT = 'input';
	const FIELD_TYPE_TEXT = 'text';
	const FIELD_TYPE_PASSWORD = 'password';
	const FIELD_TYPE_UPLOAD = 'upload';
	const FIELD_TYPE_TEXTAREA = 'textarea';
	const FIELD_TYPE_DROPDOWN = 'dropdown';
	const FIELD_TYPE_MULTISELECT = 'multiselect';
	const FIELD_TYPE_FIELDSET = 'fieldset';
	const FIELD_TYPE_CHECKBOX = 'checkbox';
	const FIELD_TYPE_RADIO = 'radio';
	const FIELD_TYPE_SUBMIT = 'submit';
	const FIELD_TYPE_LABEL = 'label';
	const FIELD_TYPE_RESET = 'reset';
	const FIELD_TYPE_BUTTON = 'button';

	protected $type;

	public function __construct() {

	}

	/**
	 * Generates the form field
	 * @return string
	 * @access public
	 * @since 1.0
	 * @throws UnexpectedValueException
	 */
	abstract public function generateField();

	/**
	 * returns the form field object for the type specified.
	 *
	 * @param type $type
	 * @return Object
	 * @since 1.0
	 * @throws UnexpectedValueException
	 */
	public static function getFormField($type) {
		switch ($type) {
			case self::FIELD_TYPE_HIDDEN:
				$field = new FormField_Hidden();
				break;

			case self::FIELD_TYPE_INPUT:
			case self::FIELD_TYPE_TEXT:

				break;

			case self::FIELD_TYPE_PASSWORD:

				break;

			case self::FIELD_TYPE_UPLOAD:

				break;

			case self::FIELD_TYPE_TEXTAREA:

				break;

			case self::FIELD_TYPE_DROPDOWN:

				break;

			case self::FIELD_TYPE_MULTISELECT:

				break;

			case self::FIELD_TYPE_FIELDSET:

				break;

			case self::FIELD_TYPE_CHECKBOX:

				break;

			case self::FIELD_TYPE_RADIO:

				break;

			case self::FIELD_TYPE_SUBMIT:

				break;

			case self::FIELD_TYPE_LABEL:

				break;

			case self::FIELD_TYPE_RESET:

				break;

			case self::FIELD_TYPE_BUTTON:

				break;

			default:
				throw new UnexpectedValueException("invalid form field defined");
		}

		return $field;
	}

	public function setType($type) {
		$this->type = trim($type);
	}

}

?>
