<?php

use PHPUnit\Framework\TestCase;

 abstract class CITest extends TestCase {

    private $CI;

    private $data;

    public function setUp() {
        // Load CI instance normally
        $this->CI = &get_instance();
    }
  }