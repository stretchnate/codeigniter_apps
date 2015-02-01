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
     * generates a single <select> field for the Form object using the CI form_helper functions
     *
     * @author stretch
     */
    class Form_Field_Select extends Form_Field implements Form_Field_Interface {

        protected $options = array();
        protected $selected_option;

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
            $name = $this->getSelectName();

            $attributes = $this->stringifyAttributes();

            $this->renderField( form_dropdown( $name, $this->options, $this->selected_option, $attributes ) );
        }

        public function addOption( $value, $content ) {
            $this->options[$value] = $content;
            return $this;
        }

        /**
         * converts $this->attributes to a string
         *
         * @return string
         */
        protected function stringifyAttributes() {
            $attributes = '';
            foreach( $this->filterAttributes() as $attribute => $value ) {
                $attributes .= " " . $attribute . "='" . $value . "'";
            }
            $attributes .= isset( $this->element_javascript ) ? " " . $this->element_javascript : '';

            return $attributes;
        }

        /**
         * returns the field name and unsets it from $this->attributes
         * @return string
         */
        protected function getSelectName() {
            $name = $this->attributes['name'];
            unset( $this->attributes['name'] );

            return $name;
        }

        /**
         * set options all at once as a single or multidimensional associative array of options.
         * $key = value and $value = text. if multi, optgroup will be created for each dimension.
         *
         * @param array $options
         * @return \FormField_Select
         */
        public function setOptions( $options ) {
            $this->options = $options;
            return $this;
        }

        /**
         * set the selected option
         *
         * @param string $selected_option
         * @return \FormField_Select
         */
        public function setSelectedOption( $selected_option ) {
            $this->selected_option = $selected_option;
            return $this;
        }

    }

?>
