<?php
    /**
     * copyright (C) 2013, 2014 Dustin Nate <stretchnate@gmail.com>
     * This file is part of CI_FormBuilder.
     *
     * CI_FormBuilder is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 3 of the License, or
     * (at your option) any later version.
     *
     * CI_FormBuilder is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License
     * along with CI_FormBuilder.  If not, see <http://www.gnu.org/licenses/>.
     */

    /**
     * Description of FormBuilder
     *
     * @author stretch
     */
    class FormBuilder {

        protected $form;

        public function __construct( $action = '', $method = 'post', $name = null, $id = null, $novalidate = null, $target = null, $autocomplete = null, $enctype = null ) {
            $this->form = new Form( $action, $method, $name, $id, $novalidate, $target, $autocomplete, $enctype );
        }

        /**
         * builds a very general field and returns it.
         *
         * @param  string $type
         * @param  string $name_id
         * @param  string $class
         * @param  mixed  $default_value (int/string)
         * @param  string $error_label_class
         * @return object /Form_Field_*
         * @since  1.1
         * @access public
         */
        public function buildSimpleField( $type, $name = null, $id = null, $class = null, $value = null, $error_label_class = 'error', $autofocus = null, $disabled = null, $required = null, $form = null ) {
            $field = Form::getNewField( $type );
            $field->setClass( $class );
            $field->setName( $name )->setId( $id );
            $field->setAutofocus( $autofocus );
            $field->setDisabled( $disabled );
            $field->setRequired( $required );
            $field->setForm( $form );

            if( method_exists( $field, 'setValue' ) ) {
                $field->setValue( $value );
            }

            if( $error_label_class ) {
                $field->addErrorLabel( $error_label_class, null, form_error( $field->getName() ) );
            }

            return $field;
        }

        /**
         * adds a simple field (created by $this->buildSimpleField()) to the form object
         *
         * @param  string $type
         * @param  string $name_id
         * @param  string $class
         * @param  mixed  $default_value (int/string)
         * @param  string $error_label_class
         * @return void
         * @since  1.1
         * @access public
         */
        public function addSimpleField( $type, $name = null, $id = null, $class = null, $value = null, $error_label_class = 'error', $autofocus = null, $disabled = null, $required = null, $form = null ) {
            $field = $this->buildSimpleField( $type, $name, $id, $class, $value, $error_label_class, $autofocus, $disabled, $required, $form );
            $this->addFieldToForm( $field );
        }

        /**
         * adds a field to $this->form (/Form object)
         *
         * @param Form_Field_Interface $field
         * @return FormBuilder
         * @since 1.1
         * @access public
         */
        public function addFieldToForm( Form_Field_Interface $field ) {
            $this->form->addField( $field );

            return $this;
        }

        public function getForm() {
            return $this->form;
        }

    }
