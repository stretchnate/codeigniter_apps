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

        $data = json_encode([]);
        $response = $this->post($this->target, $data);

        return new \Plaid\Categories($this->parseResponse($response));
    }
}