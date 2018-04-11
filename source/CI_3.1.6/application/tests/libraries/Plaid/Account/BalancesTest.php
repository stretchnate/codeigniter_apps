<?php
    namespace Plaid\Account;

    class BalancesTest extends \CITest {

        public function setUp() {
            parent::setUp();
            $this->data = new \stdClass();
            $this->data->available = 100;
            $this->data->current = 110;
            $this->data->limit = null;
        }

        /**
         * @covers \Plaid\Account\Balances::__construct
         * @return \Plaid\Account\Balances
         */
        public function testLoad() {
            $balances = new Balances($this->data);

            $this->assertInstanceOf('Plaid\Account\Balances', $balances);

            return $balances;
        }

        /**
         * @covers \Plaid\Account\Balances::getAvailable
         * @depends testLoad
         * @param \Plaid\Account\Balances $balances
         */
        public function testGetAvailable($balances) {
            $this->assertEquals($this->data->available, $balances->getAvailable());
        }
    
        /**
         * @covers \Plaid\Account\Balances::getAvailable
         * @depends testLoad
         * @param \Plaid\Account\Balances $balances
         */
        public function testGetCurrent($balances) {
            $this->assertEquals($this->data->current, $balances->getCurrent());
        }
    
        /**
         * @covers \Plaid\Account\Balances::getAvailable
         * @depends testLoad
         * @param \Plaid\Account\Balances $balances
         */
        public function testGetLimit($balances) {
            $this->assertEquals($this->data->limit, $balances->getLimit());
        }
    }