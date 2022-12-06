<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 3/4/2019
 * Time: 3:45 PM
 */

abstract class Structure {

    /**
     * @var string
     */
    protected $order_by;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var array
     */
    protected $operators = [];

    /**
     * @return string
     */
    public function getOrderBy() {
        return $this->order_by;
    }

    /**
     * @param string $order_by
     * @return Structure
     */
    public function setOrderBy($order_by) {
        $this->order_by = $order_by;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return Structure
     */
    public function setLimit($limit) {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return array
     */
    public function getOperators() {
        return $this->operators;
    }

    /**
     * @param array $operators
     * @return Structure
     */
    public function setOperators($operators) {
        $this->operators = $operators;
        return $this;
    }
}