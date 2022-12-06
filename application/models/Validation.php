<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/4/18
 * Time: 9:51 PM
 */

abstract class Validation extends \CI_Model {

    /**
     * @var SimpleValidation
     */
    protected $simple_validation;

    public function __construct() {
        parent::__construct();

        $this->simple_validation = new SimpleValidation();
    }
}