<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class N8_Controller extends CI_Controller {

    protected $view;

	function __construct() {
		parent::__construct();
	}

    /**
     * callback function to validate the recaptcha field
     *
     * @return boolean
     */
    public function validateCaptcha() {
        return Validator::validateCaptcha(
                    $this->input->server( "REMOTE_ADDR" ),
                    $this->input->post( "recaptcha_challenge_field" ),
                    $this->input->post( "recaptcha_response_field" )
                );
    }
}
