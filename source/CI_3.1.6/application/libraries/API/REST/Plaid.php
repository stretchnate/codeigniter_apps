<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/7/18
 * Time: 8:09 PM
 */

namespace API\REST;

abstract class Plaid extends \API\REST {

    public function __construct() {
        parent::__construct();
    }

    public function start() {
        $this->ch = curl_init();

        curl_setopt($this->ch,CURLOPT_FORBID_REUSE, true);
        curl_setopt($this->ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }
}