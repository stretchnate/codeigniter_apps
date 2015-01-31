<?php
    /**
     * Description of Iterator
     *
     * @author stretch
     */
    abstract class BaseIterator extends N8_Model implements Iterator {

        protected $position = 0;
        protected $items_array = array();
        protected $items_class;

        public function __construct($items_class) {
            parent::__construct();
            $this->items_class = $items_class;
        }

        /**
         * returns the current Member object
         *
         * @return \Member
         */
        public function current() {
            return $this->items_array[$this->position];
        }

        /**
         * returns the position in the members_array
         *
         * @return int
         */
        public function key() {
            return $this->position;
        }

        /**
         * increments the position by 1
         *
         * @return void
         */
        public function next() {
            $this->position++;
        }

        /**
         * resets the position to 0
         *
         * @return void
         */
        public function rewind() {
            $this->position = 0;
        }

        /**
         * determines if the current position is valid
         *
         * @return boolean
         */
        public function valid() {
            return (isset($this->items_array[$this->position]) && $this->items_array[$this->position] instanceof $this->items_class);
        }
    }
