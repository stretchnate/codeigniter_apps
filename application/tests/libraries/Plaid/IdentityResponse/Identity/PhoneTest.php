<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace Plaid\IdentityResponse\Identity;

	/**
	 * Description of PhoneTest
	 *
	 * @author stretch
	 */
	class PhoneTest extends \CITest {
		
		public function setUp() {
			parent::setUp();
			$this->data = new \stdClass();
			$this->data->data = "accountholder0@example.com";
			$this->data->primary = true;
			$this->data->type = "primary";
		}
		
		/**
		 * @covers Identity\Phone::__construct
		 * @return \Plaid\IdentityResponse\Identity\Identity\Phone
		 */
		public function testLoad() {
			$email = new Phone($this->data);
			
			$this->assertInstanceOf('Plaid\IdentityResponse\Identity\Phone', $email);
			
			return $email;
		}
		
		/**
		 * @covers Phone::getData
		 * @depends testLoad
		 * @param type $email
		 */
		public function testGetData($email) {
			$this->assertEquals($this->data->data, $email->getData());
		}
		
		/**
		 * @covers Phone::isPrimary
		 * @depends testLoad
		 * @param type $email
		 */
		public function testIsPrimary($email) {
			$this->assertTrue($email->isPrimary());
		}
		
		/**
		 * @covers Phone::getType
		 * @depends testLoad
		 * @param type $email
		 */
		public function testGetType($email) {
			$this->assertEquals($this->data->type, $email->getType());
		}
	}
	