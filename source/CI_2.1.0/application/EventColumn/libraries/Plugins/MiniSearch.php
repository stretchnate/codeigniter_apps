<?php
	/**
	 * This is the mini search form. This form only searches by zip code.
	 *
	 * @author stretch
	 */
	class Plugins_MiniSearch {

		private $form;

		public function __construct() {
			$this->buildForm();
		}

		/**
		 * Builds the mini search form
		 *
		 * @return void
		 * @since 1.0
		 */
		private function buildForm() {
			$this->form = new Form();
			$this->form->setAction('map/search');
			$this->form->addHiddenInput('search_type', 'mini_search');

			$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
			$field->setContainerClass( 'form-field' );
			$field->setFieldContainerClass( 'field-container' );
			$field->setId( 'zip' );
			$field->setName( 'zip' );
			$field->setMaxLength( '5' );
			$field->setValue('search events by zip code');
			$field->addErrorLabel( 'error', null, form_error( 'zip' ) );

			$this->form->addField( $field );

			$field = Form::getNewField( Form_Field::FIELD_TYPE_SUBMIT );
			$field->setContainerClass( 'form-field' );
			$field->setFieldContainerClass( 'field-container' );
			$field->setId( 'submit' );
			$field->setValue( 'Change this to a button and add Javascript' );

			$this->form->addField( $field );
		}

		/**
		 * renders the form
		 *
		 * @return void
		 * @since 1.0
		 */
		public function renderForm() {
			$this->form->renderForm();
		}
	}

?>
