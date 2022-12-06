<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/8/18
 * Time: 1:53 PM
 */

namespace Plaid;


use Plaid\Response\Values;
use Plaid\Response\ValuesTest;

class ResponseTest extends \CITest {

    public function setUp() {
        parent::setUp();
    }

    /**
     * @covers Response::save
     * @covers Response::insert
     * @covers Response::rowExists
     * @covers Response::buildSet
     * @throws \Exception
     */
    public function testSaveInsert() {
        $response = new Response();
        $response->getValues()->setId(ValuesTest::ID)
            ->setRequestId(ValuesTest::REQUEST_ID)
            ->setProduct(ValuesTest::PRODUCT)
            ->setData(ValuesTest::DATA);

        $this->assertTrue($response->save());
    }

    /**
     * @covers Response::load
     * @depends testSaveInsert
     * @return Response
     */
    public function testLoad() {
        $values = new Values();
        $values->setId(ValuesTest::ID);

        $response = new Response($values);

        $this->assertEquals(ValuesTest::REQUEST_ID, $response->getValues()->getRequestId());

        return $response;
    }

    /**
     * @covers Response::save
     * @covers Response::update
     * @covers Response::rowExists
     * @covers Response::buildSet
     * @depends testLoad
     * @param Response $response
     * @throws \Exception
     */
    public function testSaveUpdate($response) {
        $response->getValues()->setProduct('Identity');
        $response->save();

        $values = new Values();
        $values->setId(ValuesTest::ID);
        $r2 = new Response($values);

        $this->assertEquals('Identity', $r2->getValues()->getProduct());
    }

    public static function tearDownAfterClass() {
        $db =& get_instance()->db;
        $db->delete(Response::TABLE, ['id' => ValuesTest::ID]);
    }
}