<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/6/18
 * Time: 2:59 AM
 */

namespace Plaid\Response;


class ValuesTest extends \CITest {

    const ID = 1;

    const REQUEST_ID = 'ae34fd6945eb1a';

    const PRODUCT = 'auth';

    const DATA = '{"test":"data"}';

    const ADDED = '2018-04-08';

    public function setUp() {
        parent::setUp();
    }

    /**
     * @covers Values::setId
     * @covers Values::getId
     * @return Values;
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
     * @covers Values::setProduct
     * @covers Values::getProduct
     * @depends testGetRequestId
     * @param Values $values
     * @return Values
     */
    public function testGetProduct($values) {
        $values->setProduct(self::PRODUCT);

        $this->assertEquals(self::PRODUCT, $values->getProduct());

        return $values;
    }

    /**
     * @covers Values::setData
     * @covers Values::getData
     * @depends testGetProduct
     * @param Values $values
     * @return Values
     */
    public function testGetData($values) {
        $values->setData(self::DATA);

        $this->assertEquals(self::DATA, $values->getData());

        return $values;
    }

    /**
     * @covers Values::setAdded
     * @covers Values::getAdded
     * @depends testGetData
     * @param Values $values
     */
    public function testGetAdded($values) {
        $values->setAdded(new \DateTime(self::ADDED));

        $this->assertInstanceOf('\DateTime', $values->getAdded());
    }
}