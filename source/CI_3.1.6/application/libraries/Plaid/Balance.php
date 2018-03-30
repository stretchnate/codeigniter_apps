<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/25/18
 * Time: 7:43 PM
 */

namespace Plaid;
use Plaid\Auth\Response\Account;


/**
 * Class Balance
 *
 * @package Plaid
 */
class Balance extends Plaid {

    use RequestId, Item;

    /**
     * @var Account[]
     */
    private $accounts;

    /**
     * Balance constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        parent::__construct($raw_response);
        $this->loadAccounts($this->getRawResponse()->accounts);
    }

    /**
     * @param $raw_accounts
     */
    private function loadAccounts($raw_accounts) {
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
}