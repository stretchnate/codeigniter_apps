<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace Plaid;

	/**
	 * Description of IdentityResponseTest
	 *
	 * @author stretch
	 */
	class IdentityResponseTest extends \CITest {
		
		public function setUp() {
			parent::setUp();
			$this->data = json_decode(json_encode([
				"accounts" => [["account_id" => "vzeNDwK7KQIm4yEog683uElbp9GRLEFXGK98D",
					"balances" => [
						"available" => 100,
						"current" => 110,
						"limit" => null
					],
					"mask" => "0000",
					"name" => "Plaid Checking",
					"official_name" => "Plaid Gold Checking",
					"subtype" => "checking",
					"type" => "depository"]],
				"identity" => [
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
				],
				"item" => new \stdClass(),
				"request_id" => "dd4K4"
			]));
		}
		
		/**
		 * @covers \Plaid\IdentityResponse::__construct
		 * @covers \Plaid\IdentityResponse::loadAccounts
		 * @covers \Plaid\IdentityResponse::setItem
		 * @covers \Plaid\IdentityResponse::setRequestId
		 * @return \Plaid\IdentityResponse
		 */
		public function testLoad() {
			$ir = new IdentityResponse($this->data);
			
			$this->assertInstanceOf('Plaid\IdentityResponse', $ir);
			
			return $ir;
		}
		
		/**
		 * @covers \Plaid\IdentityResponse::getAccounts
		 * @depends testLoad
		 * @param \Plaid\IdentityResponse $ir
		 */
		public function testGetAccounts($ir) {
			$this->assertInstanceOf('Plaid\Auth\Account', $ir->getAccounts()[0]);
		}
		
		/**
		 * @covers \Plaid\IdentityResponse::getItem
		 * @depends testLoad
		 * @param \Plaid\IdentityResponse $ir
		 */
		public function testGetItem($ir) {
			$this->assertInstanceOf('stdClass', $ir->getItem());
		}
		
		/**
		 * @covers \Plaid\IdentityResponse::getRequestId
		 * @depends testLoad
		 * @param \Plaid\IdentityResponse $ir
		 */
		public function testGetRequestId($ir) {
			$this->assertEquals($this->data->request_id, $ir->getRequestId());
		}
		
		/**
		 * @covers \Plaid\IdentityResponse::getIdentity
		 * @depends testLoad
		 * @param \Plaid\IdentityResponse $ir
		 */
		public function testGetIdentity($ir) {
			$this->assertInstanceOf('Plaid\IdentityResponse\Identity', $ir->getIdentity());
		}
	}
	