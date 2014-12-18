<?php
    require_once("State.php");

    /**
     * StateIterator - fetches an array of state codes/names from site_content.content
     * parses the query result into an array of State objects
     *
     * @author stretch
     */
    class StateIterator extends SiteContent_ContentDM implements Iterator {

        private $position;
        private $states_array;

        /**
         * instantiate the class, rewind the position and populate the states_array
         */
        public function __construct() {
            parent::__construct();

            $this->rewind();
            $this->populate($this->getStateData());
        }

        /**
         * return the current node from the array
         *
         * @return \State
         */
        public function current() {
            return $this->states_array[$this->position];
        }

        /**
         * return the current position
         *
         * @return int
         */
        public function key() {
            return $this->position;
        }

        /**
         * move to the next position in the array
         *
         * @return void
         */
        public function next() {
            ++$this->position;
        }

        /**
         * set position to 0
         *
         * @return void
         */
        public function rewind() {
            $this->position = 0;
        }

        /**
         * determine if current position is valid in the states_array
         *
         * @return boolean
         */
        public function valid() {
            return isset($this->states_array[$this->position]);
        }

        /**
         * populate the states_array
         *
         * @param array $result
         * @return void
         */
        protected function populate(array $result) {
            foreach($result as $row) {
                $state_obj = new State();
                $state_obj->setStateId($row->content_id);
                $state_obj->setStateCode($row->content);
                $state_obj->setStateName($row->content_description);
                $this->states_array[] = $state_obj;
            }
        }

        /**
         * fetch state data from db
         *
         * @return array
         */
        private function getStateData() {
            $where    = array('site_id' => '');
            $where_in = array('GEO_STATE', 'US_TERRITORY');

            $query = $this->content_db->select('content_id, content, content_description')
                    ->from('CONTENT')
                    ->where($where)
                    ->where_in('qualifier', $where_in)
                    ->get();

            return $query->result();
        }
    }
