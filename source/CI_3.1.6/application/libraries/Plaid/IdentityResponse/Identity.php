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

/**
 * Class Identity
 *
 * @package Plaid\IdentityResponse
 */
class Identity {

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
     * @var \stdClass
     */
    private $raw_response;

    /**
     * Identity constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        $this->raw_response = $raw_response;
        $this->loadAddresses($this->raw_response->addresses);
        $this->loadEmails($this->raw_response->emails);
        $this->setNames($this->raw_response->names);
        $this->loadPhoneNumbers($this->raw_response->phone_numbers);
    }

    /**
     * @param \stdClass $raw_addresses
     */
    public function loadAddresses(\stdClass $raw_addresses) {
        foreach($raw_addresses as $address) {
            $this->addresses[] = new Address($address);
        }
    }

    /**
     * @param \stdClass $raw_emails
     */
    public function loadEmails(\stdClass $raw_emails) {
        foreach($raw_emails as $email) {
            $this->emails[] = new Email($email);
        }
    }

    /**
     * @param \stdClass $raw_phones
     */
    public function loadPhoneNumbers(\stdClass $raw_phones) {
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
     * @param Address[] $addresses
     * @return Identity
     */
    public function setAddresses($addresses) {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     * @return Email[]
     */
    public function getEmails() {
        return $this->emails;
    }

    /**
     * @param Email[] $emails
     * @return Identity
     */
    public function setEmails($emails) {
        $this->emails = $emails;

        return $this;
    }

    /**
     * @return array
     */
    public function getNames() {
        return $this->names;
    }

    /**
     * @param array $names
     * @return Identity
     */
    public function setNames($names) {
        $this->names = $names;

        return $this;
    }

    /**
     * @return Phone[]
     */
    public function getPhoneNumbers() {
        return $this->phone_numbers;
    }

    /**
     * @param Phone[] $phone_numbers
     * @return Identity
     */
    public function setPhoneNumbers($phone_numbers) {
        $this->phone_numbers = $phone_numbers;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }
}