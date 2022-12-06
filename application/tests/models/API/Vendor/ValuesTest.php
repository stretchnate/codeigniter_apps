<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace API\Vendor;

	/**
	 * Description of ValuesTest
	 *
	 * @author stretch
	 */
	class ValuesTest extends \CITest {

		const ID = 1;
		
		const NAME = 'unittestvendor';

		CONST USERNAME = 'bob';

		CONST PASSWORD = 'mypassword01!';

		CONST CREDENTIALS = '{something:fornothing}';

		CONST ADDED_DATE = '2018-03-30';

		CONST DISABLED_DATE = '2018-04-01';
		
		public function setUp() {
			parent::setUp();
		}
		
		/**
		 * @covers \API\Vendor\Values::setId
		 * @covers \API\Vendor\Values::getId
		 * @return \API\Vendor\Values
		 */
		public function testGetId() {
			$values = new Values();
			
			$values->setId(self::ID);
			
			$this->assertEquals(self::ID, $values->getId());
			
			return $values;
		}
		
		/**
		 * @covers \API\Vendor\Values::setName
		 * @covers \API\Vendor\Values::getName
		 * @depends testGetId
		 * @param \API\Vendor\Values
		 * @return \API\Vendor\Values
		 */
		public function testGetName($values) {
			$values->setName(self::NAME);
			
			$this->assertEquals(self::NAME, $values->getName());
			
			return $values;
		}
		
		/**
		 * @covers \API\Vendor\Values::setUserName
		 * @covers \API\Vendor\Values::getUserName
		 * @depends testGetName
		 * @param \API\Vendor\Values
		 * @return \API\Vendor\Values
		 */
		public function testGetUserName($values) {
			$values->setUserName(self::USERNAME);
			
			$this->assertEquals(self::USERNAME, $values->getUserName());
			
			return $values;
		}
		
		/**
		 * @covers \API\Vendor\Values::setPassword
		 * @covers \API\Vendor\Values::getPassword
		 * @depends testGetUserName
		 * @param \API\Vendor\Values
		 * @return \API\Vendor\Values
		 */
		public function testGetPassword($values) {
			$values->setPassword(self::PASSWORD);
			
			$this->assertEquals(self::PASSWORD, $values->getPassword());
			
			return $values;
		}
		
		/**
		 * @covers \API\Vendor\Values::setCredentials
		 * @covers \API\Vendor\Values::getCredentials
		 * @depends testGetPassword
		 * @param \API\Vendor\Values
		 * @return \API\Vendor\Values
		 */
		public function testGetCredentials($values) {
			$values->setCredentials(self::CREDENTIALS);
			
			$this->assertEquals(self::CREDENTIALS, $values->getCredentials());
			
			return $values;
		}
		
		/**
		 * @covers \API\Vendor\Values::setAddedDate
		 * @covers \API\Vendor\Values::getAddedDate
		 * @depends testGetCredentials
		 * @param \API\Vendor\Values
		 * @return \API\Vendor\Values
		 */
		public function testGetAddedDate($values) {
			$date = new \DateTime(self::ADDED_DATE);
			$values->setAddedDate($date);
			
			$this->assertInstanceOf('\DateTime', $values->getAddedDate());
			
			return $values;
		}
		
		/**
		 * @covers \API\Vendor\Values::setDisabledDate
		 * @covers \API\Vendor\Values::getDisabledDate
		 * @depends testGetAddedDate
		 * @param \API\Vendor\Values
		 * @return \API\Vendor\Values
		 */
		public function testGetDisabledDate($values) {
			$date = new \DateTime(self::DISABLED_DATE);
			$values->setDisabledDate($date);
			
			$this->assertInstanceOf('\DateTime', $values->getDisabledDate());
			
			return $values;
		}
	}
	