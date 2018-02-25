<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 9:42 PM
 */

namespace Plaid;


use Plaid\Auth\Response\Account;
use Plaid\IdentityResponse\Identity;

/**
 * Class IdentityResponse
 *
 * @package Plaid
 */
class IdentityResponse {

    /**
     * @var Account[]
     */
    private $accounts;

    /**
     * @var Identity
     */
    private $identity;

    /**
     * @var object
     */
    private $item;

    /**
     * @var string
     */
    private $request_id;

    /**
     * @var \stdClass
     */
    private $raw_response;

    /**
     * IdentityResponse constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        $this->raw_response = $raw_response;
        $this->loadAccounts($this->raw_response->accounts);
        $this->setIdentity(new IdentityResponse\Identity($this->raw_response->identity));
        $this->setItem($this->raw_response->item);
        $this->setRequestId($this->raw_response->request_id);
    }

    /**
     * @param \stdClass $raw_accounts
     */
    public function loadAccounts(\stdClass $raw_accounts) {
        foreach($raw_accounts as $account) {
            $this->accounts[] = new Account($account);
        }
    }

    /**
     * @return Account[]
     */
    public function getAccounts() {
        return $this->accounts;
    }

    /**
     * @param Account[] $accounts
     * @return IdentityResponse
     */
    public function setAccounts($accounts) {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * @return Identity
     */
    public function getIdentity() {
        return $this->identity;
    }

    /**
     * @param Identity $identity
     * @return IdentityResponse
     */
    public function setIdentity($identity) {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @return object
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * @param object $item
     * @return IdentityResponse
     */
    public function setItem($item) {
        $this->item = $item;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequestId() {
        return $this->request_id;
    }

    /**
     * @param string $request_id
     * @return IdentityResponse
     */
    public function setRequestId($request_id) {
        $this->request_id = $request_id;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }
}