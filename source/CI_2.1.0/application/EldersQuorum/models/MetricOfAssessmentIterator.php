<?php
    /**
     * MetricOfAssessmentIterator: loads and iterates through the data from metric_of_assessment table
     *
     * @author stretch
     */
    class MetricOfAssessmentIterator extends BaseIterator {

        /**
         * class constructor
         *
         * @param int $unit_id
         */
        public function __construct($unit_id) {
            parent::__construct('MetricOfAssessment');

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
                    $this->items_array[] = new MetricOfAssessment($row['metric_id']);
                }
            } else {
                throw new UnexpectedValueException(__METHOD__ . ' - invalid unit id provided');
            }
        }

        /**
         * returns the number of nodes in the items_array array
         *
         * @return int
         */
        public function count() {
            return count($this->items_array);
        }
    }
