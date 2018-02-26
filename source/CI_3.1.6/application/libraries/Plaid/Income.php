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

    /**
     * @var object
     */
    private $item;

    /**
     * @var \Plaid\Income\Response\Income
     */
    private $income;

    /**
     * @var string
     */
    private $request_id;

    /**
     * Income constructor.
     *
     * @param $raw_response
     */
    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->item = $this->raw_response->item;
        $this->income = new Income\Response\Income($this->raw_response->income);
        $this->request_id = $this->raw_response->request_id;
    }

    /**
     * @return object
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * @return Income\Response\Income
     */
    public function getIncome() {
        return $this->income;
    }

    /**
     * @return string
     */
    public function getRequestId() {
        return $this->request_id;
    }
}