<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 9:57 PM
 */

namespace Plaid\IdentityResponse;


use Plaid\IdentityResponse\Identity\Address;
use Plaid\IdentityResponse\Identity\Email;
use Plaid\IdentityResponse\Identity\Phone;
use Plaid\Plaid;

/**
 * Class Identity
 *
 * @package Plaid\IdentityResponse
 */
class Identity extends Plaid {

    /**
     * @var Address[]
     */
    private $addresses;

    /**
     * @var Email[]
     */
    private $emails;

    /**
     * @var array
     */
    private $names;

    /**
     * @var Phone[]
     */
    private $phone_numbers;

    /**
     * Identity constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        parent::__construct($raw_response);
        $this->loadAddresses($this->getRawResponse()->addresses);
        $this->loadEmails($this->getRawResponse()->emails);
        $this->names = $this->getRawResponse()->names;
        $this->loadPhoneNumbers($this->getRawResponse()->phone_numbers);
    }

    /**
     * @param array $raw_addresses
     */
    public function loadAddresses(array $raw_addresses) {
        foreach($raw_addresses as $address) {
            $this->addresses[] = new Address($address);
        }
    }

    /**
     * @param array $raw_emails
     */
    public function loadEmails(array $raw_emails) {
        foreach($raw_emails as $email) {
            $this->emails[] = new Email($email);
        }
    }

    /**
     * @param array $raw_phones
     */
    public function loadPhoneNumbers(array $raw_phones) {
        foreach($raw_phones as $phone) {
            $this->phone_numbers[] = new Phone($phone);
        }
    }

    /**
     * @return Address[]
     */
    public function getAddresses() {
        return $this->addresses;
    }

    /**
     * @return Email[]
     */
    public function getEmails() {
        return $this->emails;
    }

    /**
     * @return array
     */
    public function getNames() {
        return $this->names;
    }

    /**
     * @return Phone[]
     */
    public function getPhoneNumbers() {
        return $this->phone_numbers;
    }
}