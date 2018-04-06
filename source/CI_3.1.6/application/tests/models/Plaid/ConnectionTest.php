<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/6/18
 * Time: 3:40 AM
 */

namespace Plaid;


use Plaid\Connection\Values;
use Plaid\Connection\ValuesTest;

class ConnectionTest extends \CITest {

    public function setUp() {
        parent::setUp();
    }

    /**
     * @covers Connection::save
     * @covers Connection::insert
     * @covers Connection::set
     * @covers Connection::rowExists
     * @covers Connection::getValues
     * @throws \Exception
     */
    public function testSaveInsert() {
        $connection = new Connection();
        $connection->getValues()->setItemId(ValuesTest::TEST_ITEM_ID)
            ->setAccountId(ValuesTest::TEST_ACCOUNT_ID)
            ->setAccessToken(ValuesTest::TEST_ACCESS_TOKEN)
            ->setTransactionsReady(ValuesTest::TEST_TRANSACTIONS_READY)
            ->setDtAdded(new \DateTime(ValuesTest::TEST_DT_ADDED));

        $this->assertTrue($connection->save());
    }

    /**
     * @covers Connection::load
     * @covers Connection::buildWhere
     * @return Connection
     * @throws \Exception
     */
    public function testLoad() {
        $values = new Values();
        $values->setItemId(ValuesTest::TEST_ITEM_ID);
        $connection = new Connection($values);

        $this->assertEquals(ValuesTest::TEST_ACCOUNT_ID, $connection->getValues()->getAccountId());

        return $connection;
    }

    /**
     * @covers Connection::save
     * @covers Connection::update
     * @covers Connection::set
     * @covers Connection::rowExists
     * @covers Connection::getValues
     * @depends testLoad
     * @param Connection $connection
     */
    public function testSaveUpdate($connection) {
        $connection->getValues()->setAccountId(2);

        $this->assertTrue($connection->save());
    }

    /**
     * remove the inserted db row
     */
    public static function tearDownAfterClass() {
        $db =& get_instance()->db;
        $db->delete(Connection::TABLE, ['item_id' => ValuesTest::TEST_ITEM_ID]);
    }
}