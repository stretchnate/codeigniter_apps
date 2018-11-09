<?php

/**
 * parent class for iterators
 */
abstract class IteratorBase extends \CI_Model implements \Iterator, \Countable {

    protected $key;

    protected $items;

    /**
     * IteratorBase constructor
     */
    public function __construct() {
        $this->key = 0;
    }

    /**
     * return count of items array
     *
     * @return int
     */
    public function count() {
        return count($this->items);
    }
    
    /**
     * return key
     *
     * @return int
     */
    public function key() {
        return $this->key;
    }

    /**
     * increment key
     */
    public function next() {
        $this->key++;
    }

    /**
     * rewind iterator
     */
    public function rewind() {
        $this->key = 0;
    }

    /**
     * @return bool
     */
    public function valid() {
        return isset($this->items[$this->key]);
    }

}