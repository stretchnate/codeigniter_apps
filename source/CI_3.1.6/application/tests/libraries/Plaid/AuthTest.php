<?php
    namespace Plaid;

    use Plaid\Auth\Response\Account;

    class AuthTest extends \CITest {

        public function setUp() {
            parent::setUp();
            $this->data = json_decode(json_encode([
                "accounts" => [[
                  "account_id" => "vzeNDwK7KQIm4yEog683uElbp9GRLEFXGK98D",
                  "balances" => [
                    "available" => 100,
                    "current" => 110,
                    "limit" => null
                  ],
                  "mask" => "0000",
                  "name" => "Plaid Checking",
                  "official_name" => "Plaid Gold Checking",
                  "subtype" => "checking",
                  "type" => "depository"
                ]],
                "numbers" => [[
                  "account" => "9900009606",
                  "account_id" => "vzeNDwK7KQIm4yEog683uElbp9GRLEFXGK98D",
                  "routing" => "011401533",
                  "wire_routing" => "021000021"
                 ]],
                "item" => new \stdClass(),
                "request_id" => "45QSn"
            ]));
        }

        /**
         * @covers Plaid\Auth::__construct
         * @covers Plaid\Auth::loadNumbers
         * @covers Plaid\Auth::loadAccounts
         * @covers Plaid\Auth::setRequestId
         * @covers Plaid\Auth::setItem
         * @return Plaid\Auth
         */
        public function testLoad() {
            $auth = new Auth($this->data);

            $this->assertInstanceOf('Plaid\Auth', $auth);

            return $auth;
        }

        /**
         * @covers Plaid\Auth::getAccounts
         * @depends testLoad
         * @param Plaid\Auth $auth
         * @return void
         */
        public function testGetAccounts($auth) {
            $this->assertInstanceOf('Plaid\Auth\Account', $auth->getAccounts()[0]);
        }

        /**
         * @covers Plaid\Auth::getNumbers
         * @depends testLoad
         * @param Plaid\Auth $auth
         * @return void
         */
        public function testGetNumbers($auth) {
            $this->assertInstanceOf('Plaid\Auth\Number', $auth->getNumbers()[0]);
        }

        /**
         * @covers Plaid\Auth::getRequestId
         * @depends testLoad
         * @param Plaid\Auth $auth
         * @return void
         */
        public function testGetRequestId($auth) {
            $this->assertEquals($this->data->request_id, $auth->getRequestId());
        }

        /**
         * @covers Plaid\Auth::getItem
         * @depends testLoad
         * @param Plaid\Auth $auth
         * @return void
         */
        public function testGetItem($auth) {
            $this->assertInstanceOf('\stdClass', $auth->getItem());
        }
    }