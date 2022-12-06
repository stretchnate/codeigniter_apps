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
    public function count(): int {
        return count($this->items);
    }
    
    /**
     * return key
     *
     * @return int
     */
    public function key(): mixed {
        return $this->key;
    }

    /**
     * increment key
     */
    public function next(): void {
        $this->key++;
    }

    /**
     * rewind iterator
     */
    public function rewind(): void {
        $this->key = 0;
    }

    /**
     * @return bool
     */
    public function valid(): bool {
        return isset($this->items[$this->key]);
    }

}