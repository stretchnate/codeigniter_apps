<?php
    if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

    /**
     * Description of login
     *
     * @author stretch
     */
    class logout extends N8_Controller {

        public function __construct() {
            parent::__construct();
        }

        public function index() {
            try {
                $this->auth->logout();
            } catch( Exception $e ) {
                $message = "there was an error logging out. Please try again.";
                Utilities::show500( $message, $e );
            }
        }
    }

?>
