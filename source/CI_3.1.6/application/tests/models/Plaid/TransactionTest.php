<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/8/18
 * Time: 9:33 PM
 */

namespace Plaid;


use Plaid\Transaction\Values;
use Plaid\Transaction\ValuesTest;

class TransactionTest extends \CITest {

    public function setUp() {
        parent::setUp();
    }

    /**
     * @covers Transaction::save
     * @covers Transaction::insert
     * @covers Transaction::rowExists
     * @covers Transaction::buildSet
     * @throws \Exception
     */
    public function testSaveInsert() {
        $transaction = new Transaction();
        $transaction->getValues()->setId(ValuesTest::ID)
            ->setRequestId(ValuesTest::REQUEST_ID)
            ->setData(ValuesTest::DATA);

        $this->assertTrue($transaction->save());
    }

    /**
     * @covers Transaction::load
     * @return Transaction
     * @throws \Exception
     */
    public function testLoad() {
        $values = new Values();
        $values->setId(ValuesTest::ID);
        $transaction = new Transaction($values);

        $this->assertEquals(ValuesTest::REQUEST_ID, $transaction->getValues()->getRequestId());

        return $transaction;
    }

    /**
     * @covers Transaction::save
     * @covers Transaction::update
     * @covers Transaction::rowExists
     * @covers Transaction::buildSet
     * @depends testLoad
     * @param Transaction $transaction
     * @throws \Exception
     */
    public function testSaveUpdate($transaction) {
        $transaction->getValues()->setRequestId('aabbccdd');
        $transaction->save();

        $values = new Values();
        $values->setId(ValuesTest::ID);
        $t2 = new Transaction($values);

        $this->assertEquals('aabbccdd', $t2->getValues()->getRequestId());
    }

    public static function tearDownAfterClass() {
        $db =& get_instance()->db;
        $db->delete(Transaction::TABLE, ['id' => ValuesTest::ID]);
    }
}