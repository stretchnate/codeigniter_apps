<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/30/2018
 * Time: 10:23 PM
 */

class N8_Cache extends CI_Cache {

    public function __construct(array $config = array()) {
        $this->valid_drivers = [
            'apc',
            'dummy',
            'file',
            'memcached',
            'redis',
            'wincache',
            'disk'
        ];

        parent::__construct($config);
    }
}