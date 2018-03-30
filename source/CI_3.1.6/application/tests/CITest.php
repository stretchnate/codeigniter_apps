<?php

use PHPUnit\Framework\TestCase;

 abstract class CITest extends TestCase {

    private $CI;

    private $data;

    public function setUp() {
        // Load CI instance normally
        $this->CI = &get_instance();
        // $this->CI->session->set_userdata('logged_user', 'phpunit');
        // $this->CI->session->set_userdata('user_id', 42);
    }
  }