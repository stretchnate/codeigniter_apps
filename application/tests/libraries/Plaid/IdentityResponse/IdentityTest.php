<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace Plaid;

	/**
	 * Description of IdentityTest
	 *
	 * @author stretch
	 */
	class IdentityTest extends \CITest {

		public function setUp() {
			parent::setUp();
			$this->data = json_decode(json_encode([
				"addresses" => [
				  [
					"accounts" => [
					  "Plaid Checking 0000",
					  "Plaid Saving 1111",
					  "Plaid CD 2222"
					],
					"data" => [
					  "city" => "Malakoff",
					  "state" => "NY",
					  "street" => "2992 Cameron Road",
					  "zip" => "14236"
					],
					"primary" => true
				  ],
				  [
					"accounts" => [
					  "Plaid Credit Card 3333"
					],
					"data" => [
					  "city" => "San Matias",
					  "state" => "CA",
					  "street" => "2493 Leisure Lane",
					  "zip" => "93405-2255"
					],
					"primary" => false
				  ]
				],
				"emails" => [
				  [
					"data" => "accountholder0@example.com",
					"primary" => true,
					"type" => "primary"
				  ]
				],
				"names" => [
				  "Alberta Bobbeth Charleson"
				],
				"phone_numbers" => [[
				  "primary" => true,
				  "type" => "home",
				  "data" => "4673956022"
				]],
			  ]));
		}
		
		/**
		 * @covers \Plaid\IdentityResponse\Identity::__construct
		 * @covers \Plaid\IdentityResponse\Identity::loadAddresses
		 * @covers \Plaid\IdentityResponse\Identity::loadEmails
		 * @covers \Plaid\IdentityResponse\Identity::loadPhoneNumbers
		 * @covers \Plaid\IdentityResponse\Identity::setNames
		 * @return \Plaid\IdentityResponse\Identity
		 */
		public function testLoad() {
			$identity = new IdentityResponse\Identity($this->data);
			
			$this->assertInstanceOf('Plaid\IdentityResponse\Identity', $identity);
			
			return $identity;
		}
		
		/**
		 * @covers \Plaid\IdentityResponse\Identity::getAddresses
		 * @depends testLoad
		 * @param \Plaid\IdentityResponse\Identity $identity
		 */
		public function testGetAddresses($identity) {
			$this->assertInstanceOf('Plaid\IdentityResponse\Identity\Address', $identity->getAddresses()[0]);
		}
		
		/**
		 * @covers \Plaid\IdentityResponse\Identity::getEmails
		 * @depends testLoad
		 * @param \Plaid\IdentityResponse\Identity $identity
		 */
		public function testGetEmails($identity) {
			$this->assertInstanceOf('Plaid\IdentityResponse\Identity\Email', $identity->getEmails()[0]);
		}
		
		/**
		 * @covers \Plaid\IdentityResponse\Identity::getnNames
		 * @depends testLoad
		 * @param \Plaid\IdentityResponse\Identity $identity
		 */
		public function testGetNames($identity) {
			$this->assertContains($this->data->names[0], $identity->getNames()[0]);
		}
		
		/**
		 * @covers \Plaid\IdentityResponse\Identity::getPhoneNumbers
		 * @depends testLoad
		 * @param \Plaid\IdentityResponse\Identity $identity
		 */
		public function testGetPhoneNumbers($identity) {
			$this->assertInstanceOf('Plaid\IdentityResponse\Identity\Phone', $identity->getPhoneNumbers()[0]);
		}
	}
	