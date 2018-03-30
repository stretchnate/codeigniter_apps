<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace Plaid\IdentityResponse\Identity;

	/**
	 * Description of EmailTest
	 *
	 * @author stretch
	 */
	class EmailTest extends \CITest {
		
		public function setUp() {
			parent::setUp();
			$this->data = new \stdClass();
			$this->data->data = "accountholder0@example.com";
			$this->data->primary = true;
			$this->data->type = "primary";
		}
		
		/**
		 * @covers Identity\Email::__construct
		 * @return \Plaid\IdentityResponse\Identity\Identity\Email
		 */
		public function testLoad() {
			$email = new Email($this->data);
			
			$this->assertInstanceOf('Plaid\IdentityResponse\Identity\Email', $email);
			
			return $email;
		}
		
		/**
		 * @covers Email::getData
		 * @depends testLoad
		 * @param type $email
		 */
		public function testGetData($email) {
			$this->assertEquals($this->data->data, $email->getData());
		}
		
		/**
		 * @covers Email::isPrimary
		 * @depends testLoad
		 * @param type $email
		 */
		public function testIsPrimary($email) {
			$this->assertTrue($email->isPrimary());
		}
		
		/**
		 * @covers Email::getType
		 * @depends testLoad
		 * @param type $email
		 */
		public function testGetType($email) {
			$this->assertEquals($this->data->type, $email->getType());
		}
	}
	