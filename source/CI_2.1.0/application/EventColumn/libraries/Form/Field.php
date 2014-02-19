<?php

	/**
	 * parent class of the formField objects
	 *
	 * @author stretch
	 */
	abstract class Form_Field {

		const FIELD_TYPE_HIDDEN			 = 'hidden';
		const FIELD_TYPE_INPUT			 = 'input';
		const FIELD_TYPE_TEXT			 = 'text';
		const FIELD_TYPE_PASSWORD		 = 'password';
		const FIELD_TYPE_UPLOAD			 = 'upload';
		const FIELD_TYPE_FILE			 = 'file';
		const FIELD_TYPE_TEXTAREA		 = 'textarea';
		const FIELD_TYPE_DROPDOWN		 = 'dropdown';
		const FIELD_TYPE_SELECT			 = 'select';
		const FIELD_TYPE_MULTISELECT	 = 'multiselect';
		const FIELD_TYPE_FIELDSET_OPEN	 = 'fieldset_open';
		const FIELD_TYPE_FIELDSET_CLOSE	 = 'fieldset_close';
		const FIELD_TYPE_CHECKBOX		 = 'checkbox';
		const FIELD_TYPE_RADIO			 = 'radio';
		const FIELD_TYPE_SUBMIT			 = 'submit';
		const FIELD_TYPE_LABEL			 = 'label';
		const FIELD_TYPE_RESET			 = 'reset';
		const FIELD_TYPE_BUTTON			 = 'button';
		const FIELD_TYPE_RECAPTCHA		 = 'recaptcha';

		protected $element_javascript = null;
		protected $container_class    = 'form_field';
		protected $container_id;
		protected $label;
		protected $label_id;
		protected $label_class;
		protected $label_container_id;
		protected $label_container_class = 'field_label';
		protected $field_container_id;
		protected $field_container_class = 'field_container';
		protected $error_label;
		//element attributes
		protected $attributes = array(
			'autofocus'	 => null,
			'disabled'	 => null,
			'form'		 => null,
			'name'		 => null,
			'required'	 => null,
			'id'		 => null,
			'class'		 => null
		);

		public function __construct() {
			$ci = & get_instance();
			$ci->load->helper( 'form' );
		}

		/**
		 * Generates the form field
		 * @return string
		 * @access public
		 * @since 1.0
		 * @throws UnexpectedValueException
		 */
		abstract public function generateField();

		protected function renderField( $field ) {
			?>
			<div<?=( $this->container_class ) ? " class='" . $this->container_class . "'" : ''; ?><?=isset( $this->container_id ) ? " id='" . $this->container_id . "'" : ""; ?>>
				<?=$this->generateLabel(); ?>
				<div<?=($this->field_container_id) ? " id='" . $this->field_container_id . "'" : ''; ?><?=($this->field_container_class) ? " class='" . $this->field_container_class . "'" : ''; ?>>
					<?php
					echo $field;
					if( is_object( $this->error_label ) && $this->error_label instanceof Form_Field_Label ) {
						echo $this->error_label->generateField();
					}
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * adds an error label to a form field for user friendly error messages.
		 *
		 * @param  string $class
		 * @param  string $id
		 * @param  string $content
		 * @return void
		 * @since  1.0
		 */
		public function addErrorLabel( $class = null, $id = null, $content = null ) {
			if( empty( $class ) ) {
				$class = 'error';
			}

			$this->error_label = new Form_Field_Label();

			$this->error_label->setId( 'error_' . $this->attributes['id'] );
			$this->error_label->setClass( $class );
			if( $id ) {
				$this->error_label->setId( $id );
			}

			$this->error_label->setContent( $content );
		}

		public function setErrorLabel( Form_Field_Label $error_label ) {
			$this->error_label = $error_label;
		}

		/**
		 * filters through $this->attribtues returning only attributes that are !is_null()
		 *
		 * @return array
		 * @since 1.0
		 */
		protected function filterAttributes() {
			$attributes = array( );
			foreach( $this->attributes as $attribute => $value ) {
				if( $value !== null ) {
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
		private function generateLabel() {
			if( isset( $this->label ) ) {
				$label = new Form_Field_Label();
				$label->setFor( $this->attributes['id'] );
				$label->setForm( $this->attributes['form'] );
				$label->setId( $this->label_id );
				$label->setClass( $this->label_class );
				$label->setContent( $this->label );
				?>
				<div<?=isset( $this->label_container_id ) ? " id='" . $this->label_container_id . "'" : ''; ?><?=isset( $this->label_container_class ) ? " class='" . $this->label_container_class . "'" : ''; ?>>
					<?=$label->generateField(); ?>
				</div>
				<?php
			}
		}

		public function getName() {
			return $this->attributes['name'];
		}

		public function setAutofocus( $autofocus ) {
			$this->attributes['autofocus'] = ($autofocus) ? 'autofocus' : null;
			return $this;
		}

		public function setDisabled( $disabled ) {
			$this->attributes['disabled'] = ($disabled) ? 'disabled' : null;
			return $this;
		}

		public function setForm( $form ) {
			$this->attributes['form'] = $form;
			return $this;
		}

		public function setName( $name ) {
			$this->attributes['name'] = $name;
			return $this;
		}

		public function setRequired( $required ) {
			$this->attributes['required'] = ($required) ? 'required' : null;
			return $this;
		}

		/**
		 * sets the element id if $this->label is empty
		 *
		 * @param type $id
		 * @return \Form_Field
		 */
		public function setId( $id ) {
			//we can only override the id if the label is empty
			if(!$this->label) {
				$this->attributes['id'] = $id;
			} else {
				throw new Exception("can't set ID when label is being used ".$backtrace);
			}

			return $this;
		}

		public function setClass( $class ) {
			$this->attributes['class'] = $class;
			return $this;
		}

		/**
		 * sets the label for the element and the id of the element, also sets the name of the element if name is not
		 * already present
		 *
		 * @param string $label
		 * @return \Form_Field
		 */
		public function setLabel( $label ) {
			$regex = '~[^\w]+~';
			if(!$this->attributes['name']) {
				$name = strtolower(str_replace(" ", "_", $label));
				$this->setName( preg_replace($regex, '', $name) );
			}

			$id = strtolower(str_replace(" ", "_", $label));
			$this->setId(preg_replace($regex, '', $id));

			$this->label = $label;

			return $this;
		}

		public function setElementJavascript( $js ) {
			$this->element_javascript = $js;
			return $this;
		}

		public function setContainerId( $container_id ) {
			$this->container_id = $container_id;
			return $this;
		}

		public function setContainerClass( $container_class ) {
			$this->container_class = $container_class;
			return $this;
		}

		public function setLabelId( $label_id ) {
			$this->label_id = $label_id;
			return $this;
		}

		public function setLabelClass( $label_class ) {
			$this->label_class = $label_class;
			return $this;
		}

		public function setLabelContainerId( $label_container_id ) {
			$this->label_container_id = $label_container_id;
			return $this;
		}

		public function setLabelContainerClass( $label_container_class ) {
			$this->label_container_class = $label_container_class;
			return $this;
		}

		public function setFieldContainerId( $field_container_id ) {
			$this->field_container_id = $field_container_id;
			return $this;
		}

		public function setFieldContainerClass( $field_container_class ) {
			$this->field_container_class = $field_container_class;
			return $this;
		}

		/**
		 * will override $this->attributes for whatever element is being created, use with caution
		 *
		 * @param array $attributes
		 * @return object
		 */
		public function setAttributes( array $attributes ) {
			$this->attributes = $attributes;
			return $this;
		}

	}
?>
