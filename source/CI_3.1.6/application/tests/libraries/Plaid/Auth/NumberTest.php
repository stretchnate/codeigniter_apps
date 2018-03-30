<?php

    use Plaid\Auth\Number;

    class NumberTest extends \CITest {

        public function setUp() {
            parent::setUp();
            $this->data = json_decode(json_encode([
                    "account" => "9900009606",
                    "account_id" => "vzeNDwK7KQIm4yEog683uElbp9GRLEFXGK98D",
                    "routing" => "011401533",
                    "wire_routing" => "021000021"
               ]));
        }

        /**
         * @covers Plaid\Auth\Number::__construct
         * @return Plaid\Auth\Number
         */
        public function testLoad() {
            $number = new Number($this->data);

            $this->assertInstanceOf('Plaid\Auth\Number', $number);

            return $number;
        }

        /**
         * @covers Plaid\Auth\Number::getAccount
         * @depends testLoad
         * @param Plaid\Auth\Number $number
         */
        public function testGetAccount($number) {
            $this->assertEquals($this->data->account, $number->getAccount());
        }

        /**
         * @covers Plaid\Auth\Number::getAccountId
         * @depends testLoad
         * @param Plaid\Auth\Number $number
         */
        public function testGetAccountId($number) {
            $this->assertEquals($this->data->account_id, $number->getAccountId());
        }

        /**
         * @covers Plaid\Auth\Number::getRouting
         * @depends testLoad
         * @param Plaid\Auth\Number $number
         */
        public function testGetRouting($number) {
            $this->assertEquals($this->data->routing, $number->getRouting());
        }

        /**
         * @covers Plaid\Auth\Number::getWireRouting
         * @depends testLoad
         * @param Plaid\Auth\Number $number
         */
        public function testGetWireRouting($number) {
            $this->assertEquals($this->data->wire_routing, $number->getWireRouting());
        }
    }
