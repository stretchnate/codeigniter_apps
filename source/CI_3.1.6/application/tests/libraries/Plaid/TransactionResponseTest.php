<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace Plaid;

	/**
	 * Description of Transaction
	 *
	 * @author stretch
	 */
	class TransactionResponseTest extends \CITest {

		public function setUp() {
			parent::setUp();
			$this->data = json_decode(json_encode([
				"accounts" => [[
                  "account_id" => "vzeNDwK7KQIm4yEog683uElbp9GRLEFXGK98D",
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
				"transactions" => [[
				   "account_id" => "vokyE5Rn6vHKqDLRXEn5fne7LwbKPLIXGK98d",
				   "amount" => 2307.21,
				   "category" => [
					 "Shops",
					 "Computers and Electronics"
				   ],
				   "category_id" => "19013000",
				   "date" => "2017-01-29",
				   "location" => [
					"address" => "300 Post St",
					"city" => "San Francisco",
					"state" => "CA",
					"zip" => "94108",
					"lat" => null,
					"lon" => null
				   ],
				   "name" => "Apple Store",
				   "payment_meta" => new \stdClass(),
				   "pending" => false,
				   "pending_transaction_id" => null,
				   "account_owner" => null,
				   "transaction_id" => "lPNjeW1nR6CDn5okmGQ6hEpMo4lLNoSrzqDje",
				   "transaction_type" => "place"
				  ], [
				   "account_id" => "XA96y1wW3xS7wKyEdbRzFkpZov6x1ohxMXwep",
				   "amount" => 78.5,
				   "category" => [
					 "Food and Drink",
					 "Restaurants"
				   ],
				   "category_id" => "13005000",
				   "date" => "2017-01-29",
				   "location" => [
					 "address" => "262 W 15th St",
					 "city" => "New York",
					 "state" => "NY",
					 "zip" => "10011",
					 "lat" => 40.740352,
					 "lon" => -74.001761
				   ],
				   "name" => "Golden Crepes",
				   "payment_meta" => new \stdClass(),
				   "pending" => false,
				   "pending_transaction_id" => null,
				   "account_owner" => null,
				   "transaction_id" => "4WPD9vV5A1cogJwyQ5kVFB3vPEmpXPS3qvjXQ",
				   "transaction_type" => "place"
				 ]],
				 "item" => new \stdClass(),
				 "total_transactions" => 11,
				 "request_id" => "45QSn"
			]));
		}
		
		/**
		 * @covers \Plaid\Transaction::loadAccounts
		 * @covers \Plaid\Transaction::loadTransactions
		 * @return \Plaid\Transaction
		 */
		public function testLoad() {
			$transaction = new TransactionResponse($this->data);
			
			$this->assertInstanceOf('Plaid\TransactionResponse', $transaction);
			
			return $transaction;
		}
		
		/**
		 * @covers \Plaid\Transaction::getAccounts
		 * @depends testLoad
		 * @param \Plaid\Transaction $transaction
		 */
		public function testGetAccounts($transaction) {
			$this->assertInstanceOf('Plaid\Auth\Account', $transaction->getAccounts()[0]);
		}
		
		/**
		 * @covers \Plaid\Transaction::getTransactions
		 * @depends testLoad
		 * @param \Plaid\Transaction $transaction
		 */
		public function testGetTransactions($transaction) {
			$this->assertInstanceOf('Plaid\TransactionResponse\Transaction', $transaction->getTransactions()[0]);
		}
		
		/**
		 * @covers \Plaid\Transaction::getTotalTransactions
		 * @depends testLoad
		 * @param \Plaid\Transaction $transaction
		 */
		public function testGetTotalTransactions($transaction) {
			$this->assertEquals($this->data->total_transactions, $transaction->getTotalTransactions());
		}
	}
	