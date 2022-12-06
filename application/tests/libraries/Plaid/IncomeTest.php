<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace Plaid;

	/**
	 * Description of Income
	 *
	 * @author stretch
	 */
	class IncomeTest extends \CITest {
		
		public function setUp() {
			parent::setUp();
			$this->data = json_decode(json_encode([
				"item" => new \stdClass(),
				"income" => [
				  "income_streams" => [
					[
					  "confidence" => 1,
					  "days" => 518,
					  "monthly_income" => 1601,
					  "name" => "PLAID"
					],
					[
					  "confidence" => 0.95,
					  "days" => 415,
					  "monthly_income" => 1530,
					  "name" => "BAGUETTES INC"
					]
				  ],
				  "last_year_income" => 28072,
				  "last_year_income_before_tax" => 38681,
				  "projected_yearly_income" => 19444,
				  "projected_yearly_income_before_tax" => 26291,
				  "max_number_of_overlapping_income_streams" => 2,
				  "number_of_income_streams" => 2
				],
				"request_id" => "x9a1xa"
			  ]));
		}
		
		/**
		 * @covers \Plaid\Income::__construct
		 * @covers \Plaid\Income::setRequestId
		 * @covers \Plaid\Income::setItem
		 * @return \Plaid\Income
		 */
		public function testLoad() {
			$income = new Income($this->data);
			
			$this->assertInstanceOf('Plaid\Income', $income);
			
			return $income;
		}
		
		/**
		 * @covers \Plaid\Income::getIncome
		 * @depends testLoad
		 * @param type $income
		 */
		public function testGetIncome($income) {
			$this->assertInstanceOf('Plaid\Income\Income', $income->getIncome());
		}
		
		/**
		 * @covers \Plaid\Income::getRequestId
		 * @depends testLoad
		 * @param type $income
		 */
		public function testGetRequestId($income) {
			$this->assertEquals($this->data->request_id, $income->getRequestId());
		}
		
		/**
		 * @covers \Plaid\Income::getItem
		 * @depends testLoad
		 * @param type $income
		 */
		public function testGetItem($income) {
			$this->assertInstanceOf('\stdClass', $income->getItem());
		}
	}
	