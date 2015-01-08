<?php
    /**
     * MetricOfAssessmentIterator: loads and iterates through the data from metric_of_assessment table
     *
     * @author stretch
     */
    class MetricOfAssessmentIterator extends N8_Model implements Iterator {

        private $position      = 0;
        private $metrics_array = array();

        /**
         * class constructor
         *
         * @param int $unit_id
         */
        public function __construct($unit_id) {
            parent::__construct();

            $this->loadMetricsByUnit($unit_id);
        }

        /**
         * loads the metrics data into Member objects
         *
         * @param int $unit_id
         * @throws UnexpectedValueException
         * @return void
         */
        private function loadMetricsByUnit($unit_id) {
            if(  is_numeric( $unit_id)) {
                $result = $this->db->select('metric_id')
                                    ->from('metric_of_assessment')
                                    ->where('unit_id', $unit_id)
                                    ->get();

                foreach($result->result_array() as $row) {
                    //have the metric of assessment class load itself so we don't have to change
                    //code in the iterator everytime a new field is added to metric of assessment
                    $this->metrics_array[] = new MetricOfAssessment($row['metric_id']);
                }
            } else {
                throw new UnexpectedValueException(__METHOD__ . ' - invalid unit id provided');
            }
        }

        /**
         * returns the current Member object
         *
         * @return \Member
         */
        public function current() {
            return $this->metrics_array[$this->position];
        }

        /**
         * returns the position in the metrics_array
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
            ++$this->position;
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
            return (isset($this->metrics_array[$this->position]) && $this->metrics_array[$this->position] instanceof MetricOfAssessment);
        }

        /**
         * returns the number of nodes in the metrics_array array
         *
         * @return int
         */
        public function count() {
            return count($this->metrics_array);
        }
    }
