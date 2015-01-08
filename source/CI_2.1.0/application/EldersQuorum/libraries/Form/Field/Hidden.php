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
     * generates a single <input type="hidden"> field for the Form object using the CI form_helper functions
     *
     * @author stretch
     */
    class Form_Field_Hidden extends Form_Field implements Form_Field_Interface {

        private $hidden_elements_array = array();

        public function __construct() {
            parent::__construct();
            $this->attributes['value'] = null;
        }

        /**
         * Generates the form field
         *
         * @return string
         * @access public
         * @since 1.0
         */
        public function generateField() {
            if( ! empty( $this->hidden_elements_array ) ) {
                echo form_hidden( $this->hidden_elements_array );
            } else {
                echo form_hidden( $this->attributes['name'], $this->attributes['value'] );
            }
        }

        public function setHiddenElementsArray( array $hidden_elements_array ) {
            $this->hidden_elements_array = $hidden_elements_array;
            return $this;
        }

        public function setValue( $value ) {
            $this->attributes['value'] = $value;
            return $this;
        }

    }

?>
