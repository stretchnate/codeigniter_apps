<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 5/15/2018
 * Time: 4:37 PM
 */

namespace API\REST\Plaid;

use API\REST\Plaid;

class Categories extends Plaid {

    private $target = 'categories/get';

    /**
     * @param $token
     * @return \Plaid\Categories
     * @throws \Exception
     */
    public function getCategories() {
        $this->start();

        $response = $this->post($this->target, '{}');

        return new \Plaid\Categories($this->parseResponse($response));
    }
}