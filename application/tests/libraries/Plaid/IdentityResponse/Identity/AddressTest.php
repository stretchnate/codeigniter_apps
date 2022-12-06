<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Plaid\IdentityResponse\Identity;

use Plaid\IdentityResponse\Identity\Address;

/**
 * Description of Address
 *
 * @author stretch
 */
class AddressTest extends \CITest {
    
    public function setUp() {
        parent::setUp();
        $this->data = json_decode(json_encode([
        "accounts" => [
          "Plaid Checking 0000",
          "Plaid Saving 1111",
          "Plaid CD 2222"
        ],
        "data" => [
          "city" => "Malakoff",
          "state" => "NY",
          "street" => "2992 Cameron Road",
          "zip" => "14236"
        ],
        "primary" => true
      ]));
    }
    
    /**
     * @covers Plaid\IdentityResponse\Identity\Address::__construct
     * 
     * @return Address
     */
    public function testLoad() {
        $address = new Address($this->data);
        
        $this->assertInstanceOf('Plaid\IdentityResponse\Identity\Address', $address);
        
        return $address;
    }
    
    /**
     * @covers Plaid\IdentityResponse\Identity\Address::getAccounts
     * @depends testLoad
     * @param Plaid\IdentityResponse\Identity\Address $address
     */
    public function testGetAccounts($address) {
        $this->assertContains($this->data->accounts[2], $address->getAccounts());
    }
    
    /**
     * @covers Plaid\IdentityResponse\Identity\Address::getData
     * @depends testLoad
     * @param Plaid\IdentityResponse\Identity\Address $address
     */
    public function testGetData($address) {
        $this->assertInstanceOf('Plaid\Location', $address->getData());
    }
    
    /**
     * @covers Plaid\IdentityResponse\Identity\Address::isPrimary
     * @depends testLoad
     * @param Plaid\IdentityResponse\Identity\Address $address
     */
    public function testIsPrimary($address) {
        $this->assertTrue($address->isPrimary());
    }
}
