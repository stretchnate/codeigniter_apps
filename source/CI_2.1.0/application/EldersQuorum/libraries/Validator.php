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
    }
