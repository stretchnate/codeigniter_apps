<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 5/23/2018
 * Time: 5:10 PM
 */

namespace Plaid;

/**
 * Class AccessToken
 *
 * @package Plaid
 */
class AccessToken extends Plaid {

    /**
     * @var string
     */
    private $access_token;
    /**
     * @var string
     */
    private $item_id;

    /**
     * AccessToken constructor.
     *
     * @param $raw_response
     */
    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->access_token = $raw_response->access_token;
        $this->item_id = $raw_response->item_id;
    }

    /**
     * @return string
     */
    public function getAccessToken() {
        return $this->access_token;
    }

    /**
     * @return string
     */
    public function getItemId() {
        return $this->item_id;
    }
}