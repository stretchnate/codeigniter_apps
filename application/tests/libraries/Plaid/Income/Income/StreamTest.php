<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace Plaid\Income\Income;

	/**
	 * Description of Stream
	 *
	 * @author stretch
	 */
	class StreamTest extends \CITest {

		public function setUp() {
			parent::setUp();
			$this->data = new \stdClass();
			$this->data->confidence = 1;
			$this->data->days = 518;
			$this->data->monthly_income = 1601;
			$this->data->name = "PLAID";
		}
		
		/**
		 * @covers \Plaid\Income\Income\Stream::__construct
		 * @return \Plaid\Income\Income\Stream
		 */
		public function testLoad() {
			$stream = new Stream($this->data);
			
			$this->assertInstanceOf('Plaid\Income\Income\Stream', $stream);
			
			return $stream;
		}
		
		/**
		 * @covers \Plaid\Income\Income\Stream::getConfidence
		 * @depends testLoad
		 * @param \Plaid\Income\Income\Stream $stream
		 */
		public function testGetConfidence($stream) {
			$this->assertEquals($this->data->confidence, $stream->getConfidence());
		}
		
		/**
		 * @covers \Plaid\Income\Income\Stream::getDays
		 * @depends testLoad
		 * @param \Plaid\Income\Income\Stream $stream
		 */
		public function testGetDays($stream) {
			$this->assertEquals($this->data->days, $stream->getDays());
		}
		
		/**
		 * @covers \Plaid\Income\Income\Stream::getMonthlyIncome
		 * @depends testLoad
		 * @param \Plaid\Income\Income\Stream $stream
		 */
		public function testGetMonthlyIncome($stream) {
			$this->assertEquals($this->data->monthly_income, $stream->getMonthlyIncome());
		}
		
		/**
		 * @covers \Plaid\Income\Income\Stream::getName
		 * @depends testLoad
		 * @param \Plaid\Income\Income\Stream $stream
		 */
		public function testGetName($stream) {
			$this->assertEquals($this->data->name, $stream->getName());
		}
	}
	