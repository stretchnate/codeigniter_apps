<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/22/18
 * Time: 6:54 PM
 */

namespace Plaid\Auth\Response;
use Plaid\Plaid;


/**
 * Class Number
 * @package Plaid\Response
 */
class Number extends Plaid {

    /**
     * @var string
     */
    private $account;

    /**
     * @var string
     */
    private $account_id;

    /**
     * @var string
     */
    private $routing;

    /**
     * @var string
     */
    private $wire_routing;

    /**
     * Number constructor.
     * @param $raw_response
     */
    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->account = $this->getRawResponse()->account;
        $this->account_id = $this->getRawResponse()->account_id;
        $this->routing = $this->getRawResponse()->routing;
        $this->wire_routing = $this->getRawResponse()->wire_routing;
    }


    /**
     * @return string
     */
    public function getAccount() {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getAccountId() {
        return $this->account_id;
    }

    /**
     * @return string
     */
    public function getRouting() {
        return $this->routing;
    }

    /**
     * @return string
     */
    public function getWireRouting() {
        return $this->wire_routing;
    }
}