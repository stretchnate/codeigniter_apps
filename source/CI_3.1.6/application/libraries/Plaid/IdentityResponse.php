<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 9:42 PM
 */

namespace Plaid;


use Plaid\IdentityResponse\Identity;

/**
 * Class IdentityResponse
 *
 * @package Plaid
 */
class IdentityResponse extends Plaid {

    use RequestId, Item;

    /**
     * @var Account[]
     */
    private $accounts;

    /**
     * @var Identity
     */
    private $identity;

    /**
     * IdentityResponse constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        parent::__construct($raw_response);
        $this->loadAccounts($this->getRawResponse()->accounts);
        $this->identity = new IdentityResponse\Identity($this->getRawResponse()->identity);
        $this->setItem($this->getRawResponse()->item);
        $this->setRequestId($this->getRawResponse()->request_id);
    }

    /**
     * @param array $raw_accounts
     */
    public function loadAccounts( $raw_accounts) {
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
     * @return Identity
     */
    public function getIdentity() {
        return $this->identity;
    }
}