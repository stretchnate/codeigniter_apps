<?php

use PHPUnit\Framework\TestCase;

 class CITest extends TestCase
  {
    private $CI;
    public function setUp()
    {
        // Load CI instance normally
        $this->CI = &get_instance();
        $this->CI->session->set_userdata('logged_user', 'phpunit');
        $this->CI->session->set_userdata('user_id', 42);
    }
    public function testGetPost()
    {
      $_SERVER['REQUEST_METHOD'] = 'GET';
      $_GET['foo'] = 'bar';
      $this->assertEquals('bar', $this->CI->input->get_post('foo'));
    }
  }