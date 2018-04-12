<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/10/18
 * Time: 8:38 PM
 */

namespace Plaid;


use Plaid\Metadata\Institution;

/**
 * Class Metadata
 *
 * @package Plaid
 */
class Metadata extends Plaid {

    /**
     * @var string
     */
    private $link_session_id;

    /**
     * @var Institution
     */
    private $institution;

    /**
     * @var Account[]
     */
    private $accounts;

    /**
     * Metadata constructor.
     *
     * @param $raw_response
     */
    public function __construct($raw_response) {
        if(is_array($raw_response)) {
            $raw_response = json_decode(json_encode($raw_response));
        }

        parent::__construct($raw_response);
        $this->link_session_id = $raw_response->link_session_id;
        $this->loadInstitution();
        $this->loadAccounts();
    }

    /**
     * load institution object
     */
    private function loadInstitution() {
        $this->institution = new Institution($this->getRawResponse()->institution);
    }

    /**
     * load accounts array
     */
    private function loadAccounts() {
        foreach($this->getRawResponse()->accounts as $account) {
            $this->accounts[] = new Account($account);
        }
    }

    /**
     * @return string
     */
    public function getLinkSessionId() {
        return $this->link_session_id;
    }

    /**
     * @return Institution
     */
    public function getInstitution() {
        return $this->institution;
    }

    /**
     * @return Account[]
     */
    public function getAccounts() {
        return $this->accounts;
    }
}