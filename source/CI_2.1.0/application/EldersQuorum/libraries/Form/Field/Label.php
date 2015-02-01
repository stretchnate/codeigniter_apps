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
     * generates a single <button> field for the Form object using the CI form_helper functions
     *
     * @author stretch
     */
    class Form_Field_Label extends Form_Field implements Form_Field_Interface {

        private $for     = null;
        private $content = null;

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
            echo form_label( $this->content, $this->for, $this->filterAttributes() );
        }

        /**
         * adds the input id to the for attribute
         *
         * @param string $for
         * @return \Label
         */
        public function setFor( $for ) {
            $this->for = $for;
            return $this;
        }

        /**
         * sets the label content <label>content</label>
         *
         * @param string $content
         * @return \Label
         */
        public function setContent( $content ) {
            $this->content = $content;
            return $this;
        }

    }

?>
