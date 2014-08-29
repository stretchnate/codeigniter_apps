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
            $this->form->setAction( 'map/search' );
            $this->form->setName( 'mini_search' );
            $this->form->setId( 'mini_search' );
            $this->form->addHiddenInput( 'search_type', 'mini_search' );

            $field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
            $field->setId( 'mini_search_zip' );
            $field->setName( 'mini_search_zip' );
            $field->setContainerClass( 'mini_search_set' );
            $field->setFieldContainerClass( 'mini_search_field' );
            $field->setMaxLength( '5' );
            $field->setMinLength( '5' );
            $field->setValue( 'search events by zip code' );
            $field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

            $this->form->addField( $field );

            $field = Form::getNewField( Form_Field::FIELD_TYPE_IMAGE );
            $field->setId( 'mini_search_submit' );
            $field->setContainerClass( 'mini_search_set' );
            $field->setFieldContainerClass( 'mini_search_field' );
            $field->setSrc( '/images/triangle2.jpg' );
            $field->setAlt( 'Submit' );
            $field->setHeight( '1px' );

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
