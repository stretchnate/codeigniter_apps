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
     * generates a single <select multiselect="multiselect"> field for the Form object using the CI form_helper functions
     *
     * @author stretch
     */
    class Form_Field_Select_MultiSelect extends Form_Field_Select implements Form_Field_Interface {

        protected $selected_option = array();

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

            /*
             * even though form_select will create a multiselect if the third argument
             * is an array of multiple items we still need to do this here in case the
             * third option is empty and we still want a multiselect
             */
            $this->renderField( form_multiselect( $name, $this->options, $this->selected_option, $attributes ) );
        }

        /**
         * set the selected option(s)
         *
         * @param array $selected_option
         * @return \FormField_Select
         */
        public function setSelectedOption( array $selected_option ) {
            $this->selected_option = $selected_option;
            return $this;
        }

        public function addSelectedOption( $selected_option ) {
            $this->selected_option[] = $selected_option;
            return $this;
        }

    }

?>
