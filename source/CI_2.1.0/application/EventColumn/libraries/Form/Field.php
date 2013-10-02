<?php

/**
 * parent class of the formField objects
 *
 * @author stretch
 */
abstract class Field {

	const FIELD_TYPE_HIDDEN = 'hidden';
	const FIELD_TYPE_INPUT = 'input';
	const FIELD_TYPE_TEXT = 'text';
	const FIELD_TYPE_PASSWORD = 'password';
	const FIELD_TYPE_UPLOAD = 'upload';
	const FIELD_TYPE_FILE = 'file';
	const FIELD_TYPE_TEXTAREA = 'textarea';
	const FIELD_TYPE_DROPDOWN = 'dropdown';
	const FIELD_TYPE_SELECT = 'select';
	const FIELD_TYPE_MULTISELECT = 'multiselect';
	const FIELD_TYPE_FIELDSET = 'fieldset';
	const FIELD_TYPE_CHECKBOX = 'checkbox';
	const FIELD_TYPE_RADIO = 'radio';
	const FIELD_TYPE_SUBMIT = 'submit';
	const FIELD_TYPE_LABEL = 'label';
	const FIELD_TYPE_RESET = 'reset';
	const FIELD_TYPE_BUTTON = 'button';

	protected $type;
	protected $element_javascript = null;
	protected $label;
	protected $label_container_id;
	protected $label_container_class;
	protected $field_container_id;
	protected $field_container_class;
	//element attributes
	protected $attributes = array(
	    'autofocus' => null,
	    'disabled' => null,
	    'form' => null,
	    'name' => null,
	    'required' => null,
	    'id' => null,
	    'class' => null
	);

	public function __construct() {
		$this->load->helper('form');
	}

	/**
	 * Generates the form field
	 * @return string
	 * @access public
	 * @since 1.0
	 * @throws UnexpectedValueException
	 */
	abstract public function generateField();

	protected function renderField($field) {
		echo $this->generateLabel();
		?>
		<div<?= ($this->field_container_id) ? "id='" . $this->field_container_id . "'" : ''; ?><?= ($this->field_container_class) ? "class='" . $this->field_container_class . "'" : ''; ?>>
			<?= $field; ?>
		</div>
		<?php
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
	 * generates the label for the field
	 *
	 * @return void
	 * @since 1.0
	 */
	protected function generateLabel() {
		if (isset($this->label)) {
			?>
			<div<?= isset($this->label_container_id) ? " id='" . $this->label_container_id . "'" : ''; ?><?= isset($this->label_container_class) ? " class='" . $this->label_container_class . "'" : ''; ?>>
				<label<?= isset($this->attributes['id']) ? " for='" . $this->attributes['id'] . "'" : ''; ?>><?= $this->label; ?></label>
			</div>
			<?php
		}
	}

	public function setAutofocus($autofocus) {
		$this->attributes['autofocus'] = ($autofocus) ? 'autofocus' : null;
		return $this;
	}

	public function setDisabled($disabled) {
		$this->attributes['disabled'] = ($disabled) ? 'disabled' : null;
		return $this;
	}

	public function setForm($form) {
		$this->attributes['form'] = $form;
		return $this;
	}

	public function setName($name) {
		$this->attributes['name'] = $name;
		return $this;
	}

	public function setRequired($required) {
		$this->attributes['required'] = ($required) ? 'required' : null;
		return $this;
	}

	public function setId($id) {
		$this->attributes['id'] = $id;
		return $this;
	}

	public function setClass($class) {
		$this->attributes['class'] = $class;
		return $this;
	}

	public function setType($type) {
		$this->type = trim($type);
	}

	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}

	public function setElementJavascript($js) {
		$this->element_javascript = $js;
		return $this;
	}

	public function setLabelContainerId($label_container_id) {
		$this->label_container_id = $label_container_id;
		return $this;
	}

	public function setLabelContainerClass($label_container_class) {
		$this->label_container_class = $label_container_class;
		return $this;
	}

	public function setFieldContainerId($field_container_id) {
		$this->field_container_id = $field_container_id;
		return $this;
	}

	public function setFieldContainerClass($field_container_class) {
		$this->field_container_class = $field_container_class;
		return $this;
	}

	/**
	 * will override $this->attributes for whatever element is being created, use with caution
	 *
	 * @param array $attributes
	 * @return object
	 */
	public function setAttributes(array $attributes) {
		$this->attributes = $attributes;
		return $this;
	}

}
?>
