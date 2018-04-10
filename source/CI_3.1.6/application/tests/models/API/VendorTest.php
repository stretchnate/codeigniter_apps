<?php
	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	namespace API;

	/**
	 * Description of VendorTest
	 *
	 * @author stretch
	 */
	class VendorTest extends \CITest {

		public function setUp() {
			parent::setUp();
		}
		
		/**
		 * @covers \API\Vendor::save
		 * @covers \API\Vendor::insert
		 * @uses \API\Vendor::set
		 */
		public function testSave() {
			$vendor = new Vendor();
			$vendor->getValues()->setId(Vendor\ValuesTest::ID);
			$vendor->getValues()->setName(Vendor\ValuesTest::NAME);
			$vendor->getValues()->setUsername(Vendor\ValuesTest::USERNAME);
			$vendor->getValues()->setPassword(Vendor\ValuesTest::PASSWORD);
			$vendor->getValues()->setCredentials(Vendor\ValuesTest::CREDENTIALS);
			$vendor->getValues()->setAddedDate(new \DateTime(Vendor\ValuesTest::ADDED_DATE));
			$vendor->getValues()->setDisabledDate(new \DateTime(Vendor\ValuesTest::DISABLED_DATE));
			
			$this->assertTrue($vendor->save());
		}
		
		/**
		 * @covers \API\Vendor::load
		 * @depends testSave
		 * @return \API\Vendor
		 */
		public function testLoad() {
			$values = new Vendor\Values();
			$values->setId(Vendor\ValuesTest::ID);
			
			$vendor = new Vendor();
			$vendor->load($values);
			
			$this->assertEquals(Vendor\ValuesTest::NAME, $vendor->getValues()->getName());
			
			return $vendor;
		}
		
		/**
		 * @covers \API\Vendor::save
		 * @covers \API\Vendor::update
		 * @covers \API\Vendor::set
		 * @depends testLoad
		 * @param \API\Vendor $vendor
		 */
		public function testUpdate($vendor) {
			$vendor->getValues()->setName('larry');
			$vendor->save();
			
			$values = new Vendor\Values();
			$values->setId(Vendor\ValuesTest::ID);
			$vendor1 = new Vendor($values);

			$this->assertEquals('larry', $vendor1->getValues()->getname());
		}
		
		/**
		 * @covers \API\Vendor::getValues
		 * @depends testLoad
		 * @param \API\Vendor $vendor
		 */
		public function testGetValues($vendor) {
			$this->assertInstanceOf('\API\Vendor\Values', $vendor->getValues());
		}
		/**
		 * remove db row that was inserted
		 */
		public static function tearDownAfterClass() {
			$db =& get_instance()->db;
			$db->delete('api_vendor', ['id' => Vendor\ValuesTest::ID]);
			parent::tearDownAfterClass();
		}
	}
	