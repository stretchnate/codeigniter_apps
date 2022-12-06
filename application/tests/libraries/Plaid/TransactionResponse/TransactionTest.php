<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace Plaid\TransactionResponse;

	/**
	 * Description of Transaction
	 *
	 * @author stretch
	 */
	class TransactionTest extends \CITest {

		public function setUp() {
			parent::setUp();
			$this->data = json_decode(json_encode([
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
			]));
		}
		
		/**
		 * @covers \Plaid\Transaction\Transaction::__construct
		 * @return \Plaid\Transaction\Transaction
		 */
		public function testLoad() {
			$transaction = new Transaction($this->data);
			
			$this->assertInstanceOf('Plaid\TransactionResponse\Transaction', $transaction);
			
			return $transaction;
		}
		
		/**
		 * @covers \Plaid\Transaction\Transaction::getAccountId
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetAccountId($transaction) {
			$this->assertEquals($this->data->account_id, $transaction->getAccountId());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getAmount
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetAmount($transaction) {
			$this->assertEquals($this->data->amount, $transaction->getAmount());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getAccountOwner
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetAccountOwner($transaction) {
			$this->assertEquals($this->data->account_owner, $transaction->getAccountOwner());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getCategory
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetCategory($transaction) {
			$this->assertContains($this->data->category[1], $transaction->getCategory());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getCategoryId
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetCategoryId($transaction) {
			$this->assertEquals($this->data->category_id, $transaction->getCategoryId());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getDate
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetDate($transaction) {
			$this->assertEquals($this->data->date, $transaction->getDate()->format('Y-m-d'));
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getLocation
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetLocation($transaction) {
			$this->assertInstanceOf('Plaid\Location', $transaction->getLocation());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getName
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetName($transaction) {
			$this->assertEquals($this->data->name, $transaction->getName());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getPaymentMeta
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetPaymentMeta($transaction) {
			$this->assertEquals($this->data->payment_meta, $transaction->getPaymentMeta());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::isPending
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function isPending($transaction) {
			$this->assertEquals($this->data->pending, $transaction->isPending());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getPendingTransactionId
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetPendingTransactionId($transaction) {
			$this->assertEquals($this->data->pending_transaction_id, $transaction->getPendingTransactionId());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getTransactionId
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetTransactionId($transaction) {
			$this->assertEquals($this->data->transaction_id, $transaction->getTransactionId());
		}

		/**
		 * @covers \Plaid\Transaction\Transaction::getTransactionType
		 * @depends testLoad
		 * @param \Plaid\Transaction\Transaction $transaction
		 */
		public function testGetTransactionType($transaction) {
			$this->assertEquals($this->data->transaction_type, $transaction->getTransactionType());
		}
	}
	