<?php
    /**
     * Validator: Validates form submissions
     *
     * @author stretch
     */
    class Validator {

        private $ci;

        public function __construct() {
            $this->ci =& get_instance();

            $this->ci->load->helper(array('form', 'url'));
            $this->ci->load->library('form_validation');
        }

        /**
         * validates form submissions. rules can be passed in an array or a config file can be used
         * and the index can be passed as a string
         *
         * @param mixed $rules (array, string)
         * @return boolean
         */
        public function validate($rules) {

            if( is_array( $rules ) ) {
                $this->ci->form_validation->set_rules( $rules );
                $result = $this->ci->form_validation->run();
            } else {
                //read rules from config/form_validation.php
                $result = $this->ci->form_validation->run( $rules );
            }

            return $result;
        }

        /**
         * validates the values of two variables, sets the error message if they are equal
         *
         * @param mixed $field_1
         * @param mixed $field_2
         * @return bool
         */
        public static function notEqualTo($field_1, $field_2) {
            $result = ($field_1 !== $field_2);
            $ci =& get_instance();

            if(!$result) {
                $ci->form_validation->set_message( 'notEqualTo', 'You cannot home teach yourself' );
            }

            return $result;
        }

        /**
         * validates the recaptcha field
         *
         * @param string $server
         * @param string $challenge_field
         * @param string $response_field
         * @return boolean
         */
        public static function validateCaptcha($server, $challenge_field, $response_field) {
            $response = Form_Field_Recaptcha::validate( $server, $challenge_field, $response_field);
            $ci =& get_instance();

            if( ! $response ) {
               $ci->form_validation->set_message( 'validateCaptcha', 'The captcha wasn\'t entered correctly please try again' );
            }

            return $response;
        }
    }
