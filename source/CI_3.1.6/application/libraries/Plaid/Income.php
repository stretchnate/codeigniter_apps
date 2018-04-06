<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/25/18
 * Time: 6:29 PM
 */

namespace Plaid;


/**
 * Class Income
 *
 * @package Plaid
 */
class Income extends Plaid {

    use RequestId, Item;

    /**
     * @var \Plaid\Income\Income
     */
    private $income;

   /**
     * Income constructor.
     *
     * @param $raw_response
     */
    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->income = new Income\Income($this->getRawResponse()->income);
        $this->setRequestId($this->getRawResponse()->request_id);
        $this->setItem($this->getRawResponse()->item);
    }

    /**
     * @return Income\Income
     */
    public function getIncome() {
        return $this->income;
    }
}