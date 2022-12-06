<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/4/18
 * Time: 8:44 PM
 */

class Plaid extends CI_Controller {

    public function __construct() {
    }

    public function transaction($id) {
        //use this method for a webhook for plaid to inform us when transactions are ready
    }
}