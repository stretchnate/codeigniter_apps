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
     * generates a single <input type="image"> field for the Form object
     *
     * @author stretch
     */
    class Form_Field_Input_Image extends Form_Field_Input implements Form_Field_Interface {

        public function __construct( $src = null, $alt = null, $width = null, $height = null ) {
            parent::__construct();

            $this->setSrc( $src )->setWidth( $width )->setHeight( $height )->setAlt( $alt );
        }

        /**
         * Generates the form field
         *
         * @return string
         * @access public
         * @since 1.0
         */
        public function generateField() {
            $image = '<input type="image" src="' . $this->attributes['src'] .
                    '" width="' . $this->attributes['width'] .
                    '" height="' . $this->attributes['height'] .
                    '" alt="' . $this->attributes['alt'] . '" />';

            $this->renderField( $image );
//            $this->renderField( form_submit( $this->filterAttributes(), '', $this->element_javascript ) );
        }

        public function setSrc( $src ) {
            $this->attributes['src'] = $src;
            return $this;
        }

        public function setAlt( $alt ) {
            $this->attributes['alt'] = $alt;
            return $this;
        }

        public function setWidth( $width ) {
            $this->attributes['width'] = $width;
            return $this;
        }

        public function setHeight( $height ) {
            $this->attributes['height'] = $height;
            return $this;
        }

    }

?>
