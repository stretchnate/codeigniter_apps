<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace Plaid\Income;

	/**
	 * Description of IncomeTest
	 *
	 * @author stretch
	 */
	class IncomeTest extends \CITest {

		public function setUp() {
			parent::setUp();
			$this->data = json_decode(json_encode([
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
			  ]));
		}
		
		/**
		 * @covers \Plaid\Income\Income::__construct
		 * @covers \Plaid\Income\Income::loadIncomeStreams
		 * @return \Plaid\Income\Income
		 */
		public function testLoad() {
			$income = new Income($this->data);
			
			$this->assertInstanceOf('Plaid\Income\Income', $income);
			
			return $income;
		}
		
		/**
		 * @covers Plaid\Income\Income::getIncomeStreams
		 * @depends testLoad
		 * @param \Plaid\Income\Income $income
		 */
		public function testGetIncomeStreams($income) {
			$this->assertInstanceOf('Plaid\Income\Income\Stream', $income->getIncomeStreams()[0]);
		}
		
		/**
		 * @covers \Plaid\Income\Income::getLastYearIncome
		 * @depends testLoad
		 * @param \Plaid\Income\Income $income
		 */
		public function testGetLastYearIncome($income) {
			$this->assertEquals($this->data->last_year_income, $income->getLastYearIncome());
		}

		/**
		 * @covers \Plaid\Income\Income::getLastYearIncomeBeforeTax
		 * @depends testLoad
		 * @param \Plaid\Income\Income $income
		 */
		public function testGetLastYearIncomeBeforeTax($income) {
			$this->assertEquals($this->data->last_year_income_before_tax, $income->getLastYearIncomeBeforeTax());
		}

		/**
		 * @covers \Plaid\Income\Income::getProjectedYearlyIncome
		 * @depends testLoad
		 * @param \Plaid\Income\Income $income
		 */
		public function testGetProjectedYearlyIncome($income) {
			$this->assertEquals($this->data->projected_yearly_income, $income->getProjectedYearlyIncome());
		}

		/**
		 * @covers \Plaid\Income\Income::getProjectedYearlyIncomeBeforeTax
		 * @depends testLoad
		 * @param \Plaid\Income\Income $income
		 */
		public function testGetProjectedYearlyIncomeBeforeTax($income) {
			$this->assertEquals($this->data->projected_yearly_income_before_tax, $income->getProjectedYearlyIncomeBeforeTax());
		}

		/**
		 * @covers \Plaid\Income\Income::getMaxNumberOfOverlappingIncomeStreams
		 * @depends testLoad
		 * @param \Plaid\Income\Income $income
		 */
		public function testGetMaxNumberOfOverlappingIncomeStreams($income) {
			$this->assertEquals($this->data->max_number_of_overlapping_income_streams, $income->getMaxNumberOfOverlappingIncomeStreams());
		}

		/**
		 * @covers \Plaid\Income\Income::getNumberOfIncomeStreams
		 * @depends testLoad
		 * @param \Plaid\Income\Income $income
		 */
		public function testGetNumberOfIncomeStreams($income) {
			$this->assertEquals($this->data->number_of_income_streams, $income->getNumberOfIncomeStreams());
		}
	}
	