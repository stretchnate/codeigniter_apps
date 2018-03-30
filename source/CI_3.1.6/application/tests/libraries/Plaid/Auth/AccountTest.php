<?php

    use Plaid\Auth\Account\Balances;
    use Plaid\Auth\Account;

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
         * @covers Plaid\Auth\Account::__construct
         * @covers Plaid\Auth\Account::loadBalances
         * @return Plaid\Auth\Account
         */
        public function testLoad() {
            $account = new Account($this->data);

            $this->assertInstanceOf('Plaid\Auth\Account', $account);

            return $account;
        }

        /**
         * @covers Plaid\Auth\Account::getAccountId
         * @depends testLoad
         * @param Plaid\Auth\Account
         */
        public function testGetAccountId($account) {
            $this->assertEquals($this->data->account_id, $account->getAccountId());
        }

        /**
         * @covers Plaid\Auth\Account::getBalances
         * @depends testLoad
         * @param Plaid\Auth\Account
         */
        public function testGetBalances($account) {
            $this->assertInstanceOf('Plaid\Auth\Account\Balances', $account->getBalances());
        }
    
        /**
         * @covers Plaid\Auth\Account::getMask
         * @depends testLoad
         * @param Plaid\Auth\Account
         */
        public function testGetMask($account) {
            $this->assertEquals($this->data->mask, $account->getMask());
        }
    
        /**
         * @covers Plaid\Auth\Account::getName
         * @depends testLoad
         * @param Plaid\Auth\Account
         */
        public function testGetName($account) {
            $this->assertEquals($this->data->name, $account->getName());
        }
    
        /**
         * @covers Plaid\Auth\Account::getOfficialName
         * @depends testLoad
         * @param Plaid\Auth\Account
         */
        public function testGetOfficialName($account) {
            $this->assertEquals($this->data->official_name, $account->getOfficialName());
        }
    
        /**
         * @covers Plaid\Auth\Account::getSubtype
         * @depends testLoad
         * @param Plaid\Auth\Account
         */
        public function testGetSubtype($account) {
            $this->assertEquals($this->data->subtype, $account->getSubtype());
        }
    
        /**
         * @covers Plaid\Auth\Account::getType
         * @depends testLoad
         * @param Plaid\Auth\Account
         */
        public function testGetType($account) {
            $this->assertEquals($this->data->type, $account->getType());
        }
    }
