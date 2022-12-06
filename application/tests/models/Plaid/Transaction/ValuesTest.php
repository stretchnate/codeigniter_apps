<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/8/18
 * Time: 9:14 PM
 */

namespace Plaid\Transaction;


/**
 * Class ValuesTest
 *
 * @package Plaid\Transaction
 */
class ValuesTest extends \CITest {

    const ID = 1;

    const REQUEST_ID = 'aef3291200bde';

    const DATA = '{"test":"data"}';

    const ADDED = '2018-04-08';

    public function setUp() {
        parent::setUp();
    }

    /**
     * @covers Values::setId
     * @covers Values::getId
     * @return Values
     */
    public function testGetId() {
        $values = new Values();
        $values->setId(self::ID);

        $this->assertEquals(self::ID, $values->getId());

        return $values;
    }

    /**
     * @covers Values::setRequestId
     * @covers Values::getRequestId
     * @depends testGetId
     * @param Values $values
     * @return Values
     */
    public function testGetRequestId($values) {
        $values->setRequestId(self::REQUEST_ID);

        $this->assertEquals(self::REQUEST_ID, $values->getRequestId());

        return $values;
    }

    /**
     * @covers Values::setData
     * @covers Values::getData
     * @depends testGetRequestId
     * @param Values $values
     * @return Values
     */
    public function testGetData($values) {
        $values->setData(self::DATA);

        $this->assertEquals(self::DATA, $values->getData());

        return $values;
    }

    /**
     * @covers Values::setId
     * @covers Values::getId
     * @depends testGetData
     * @param Values $values
     * @return Values
     */
    public function testGetAdded($values) {
        $values->setAdded(new \DateTime(self::ADDED));

        $this->assertInstanceOf('\DateTime', $values->getAdded());

        return $values;
    }

    /**
     * @covers Values::toArray
     * @depends testGetAdded
     * @param Values $values
     */
    public function testToArray($values) {
        $this->assertContains(self::ID, $values->toArray());
    }

    /**
     * @covers Values::setId
     * @expectedException \InvalidArgumentException
     */
    public function testSetIdException() {
        $values = new Values();
        $values->setId('bob');
    }

    /**
     * @covers Values::setId
     * @depends testGetId
     * @param Values $values
     * @expectedException \Exception
     */
    public function testOverwriteId($values) {
        $values->setId(11);
    }
}