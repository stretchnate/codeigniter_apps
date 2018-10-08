<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 10/7/2018
 * Time: 8:47 PM
 */

namespace Account;

/**
 * Class Rows - iterator for account table; holds an array of AccountDM objects
 *
 * @package Account
 */
class Rows extends \IteratorBase {

    public function __construct() {
        parent::__construct();
    }

    public function load() {

    }

    public function current() {
        // TODO: Implement current() method.
    }
}