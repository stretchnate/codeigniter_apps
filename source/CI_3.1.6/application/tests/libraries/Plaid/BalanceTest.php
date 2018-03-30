<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace Plaid;

	/**
	 * Description of BalanceTest
	 *
	 * @author stretch
	 */
	class BalanceTest extends \CITest {

		public function setUp() {
			parent::setUp();
			$this->data = json_decode(json_encode([
				"accounts" => [[
				   "account_id" => "QKKzevvp33HxPWpoqn6rI13BxW4awNSjnw4xv",
				   "balances" => [
					 "available" => 100,
					 "current" => 110,
					 "limit" => null
				   ],
				   "mask" => "0000",
				   "name" => "Plaid Checking",
				   "official_name" => "Plaid Gold Checking",
				   "subtype" => "checking",
				   "type" => "depository"
				]],
				"item" => new \stdClass(),
				"request_id" => "1zlMf"
			  ]));
		}
		
		/**
		 * @covers \Plaid\Balance::__construct
		 * @covers \Plaid\Balance::loadAccounts
		 * @covers \Plaid\Balance::setRequestId
		 * @covers \Plaid\Balance::setItem
		 * @return \Plaid\Balance
		 */
		public function testLoad() {
			$balance = new Balance($this->data);
			
			$this->assertInstanceOf('Plaid\Balance', $balance);
			
			return $balance;
		}
		
		/**
		 * @covers \Plaid\Balance::getAccounts
		 * @depends testLoad
		 * @param \Plaid\Balance $balance
		 */
		public function testGetAccounts($balance) {
			$this->assertInstanceOf('Plaid\Auth\Account', $balance->getAccounts()[0]);
		}
		
		/**
		 * @covers \Plaid\Balance::getItem
		 * @depends testLoad
		 * @param \Plaid\Balance $balance
		 */
		public function testGetItem($balance) {
			$this->assertInstanceOf('\stdClass', $balance->getItem());
		}
		
		/**
		 * @covers \Plaid\Balance::getRequestId
		 * @depends testLoad
		 * @param \Plaid\Balance $balance
		 */
		public function testGetRequestId($balance) {
			$this->assertEquals($this->data->request_id, $balance->getRequestId());
		}
	}
	