<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class TestBase extends TestCase {

    protected $CI;

    public function setUp() {
        $this->CI =& get_instance();
        $this->CI->session->set_userdata('logged_user', 'phpunit');
        $this->CI->session->set_userdata('user_id', 42);
    }
}