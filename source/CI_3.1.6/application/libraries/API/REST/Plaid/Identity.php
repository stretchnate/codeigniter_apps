<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/12/18
 * Time: 9:12 PM
 */

namespace API\REST\Plaid;

use API\REST\Plaid;

class Identity extends Plaid {

    private $target = '/identity/get';

    public function get() {
        $this->start();

        $postfields = [];
        return $this->formatResponse($this->executeCurlPOST($this->target, $postfields));
    }

    public function formatResponse($response) {
        return new \Plaid\Auth($response);
    }
}