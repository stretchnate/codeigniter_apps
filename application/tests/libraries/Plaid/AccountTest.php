<?php

    use Plaid\Account;

    class AccountTest extends \CITest {

        public function setUp() {
            parent::setUp();
            $this->data = json_decode(json_encode([
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
            ]));
        }

        /**
         * @covers Plaid\Account::__construct
         * @covers Plaid\Account::loadBalances
         * @return Plaid\Account
         */
        public function testLoad() {
            $account = new Account($this->data);

            $this->assertInstanceOf('Plaid\Account', $account);

            return $account;
        }

        /**
         * @covers Plaid\Account::getAccountId
         * @depends testLoad
         * @param Plaid\Account
         */
        public function testGetAccountId($account) {
            $this->assertEquals($this->data->account_id, $account->getAccountId());
        }

        /**
         * @covers Plaid\Account::getBalances
         * @depends testLoad
         * @param Plaid\Account
         */
        public function testGetBalances($account) {
            $this->assertInstanceOf('Plaid\Account\Balances', $account->getBalances());
        }
    
        /**
         * @covers Plaid\Account::getMask
         * @depends testLoad
         * @param Plaid\Account
         */
        public function testGetMask($account) {
            $this->assertEquals($this->data->mask, $account->getMask());
        }
    
        /**
         * @covers Plaid\Account::getName
         * @depends testLoad
         * @param Plaid\Account
         */
        public function testGetName($account) {
            $this->assertEquals($this->data->name, $account->getName());
        }
    
        /**
         * @covers Plaid\Account::getOfficialName
         * @depends testLoad
         * @param Plaid\Account
         */
        public function testGetOfficialName($account) {
            $this->assertEquals($this->data->official_name, $account->getOfficialName());
        }
    
        /**
         * @covers Plaid\Account::getSubtype
         * @depends testLoad
         * @param Plaid\Account
         */
        public function testGetSubtype($account) {
            $this->assertEquals($this->data->subtype, $account->getSubtype());
        }
    
        /**
         * @covers Plaid\Account::getType
         * @depends testLoad
         * @param Plaid\Account
         */
        public function testGetType($account) {
            $this->assertEquals($this->data->type, $account->getType());
        }
    }
