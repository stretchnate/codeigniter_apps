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
     * generates a single closing </fieldset> tag should be used in conjunction with the Form_Field_Fieldset class
     *
     * @author stretch
     */
    class Form_Field_FieldsetClose extends Form_field implements Form_Field_Interface {

        private $trailing_html = null;

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
            echo form_fieldset_close( $this->trailing_html );
        }

        /**
         * The only advantage to using this function is it permits you to pass data to it which will be added below the </field> tag.
         *
         * @param string $trailing_html
         * @return \Field_FieldsetClose
         */
        public function setTrailingHtml( $trailing_html ) {
            $this->trailing_html = $trailing_html;
            return $this;
        }

    }

?>
