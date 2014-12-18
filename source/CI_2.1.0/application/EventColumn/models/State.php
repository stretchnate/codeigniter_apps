<?php

    /**
     * this object holds the data for a given geographic state/territory
     *
     * @author stretch
     */
    class State {

        private $state_id;
        private $state_code;
        private $state_name;

        public function getStateCode() {
            return $this->state_code;
        }

        public function getStateName() {
            return $this->state_name;
        }

        public function getStateId() {
            return $this->state_id;
        }

        public function setStateCode($state_code) {
            $this->state_code = trim(strtoupper($state_code));
            return $this;
        }

        public function setStateName($state_name) {
            $this->state_name = trim(ucwords(strtolower($state_name)));
            return $this;
        }

        public function setStateId($id) {
            $this->state_id = $id;
            return $this;
        }
    }
