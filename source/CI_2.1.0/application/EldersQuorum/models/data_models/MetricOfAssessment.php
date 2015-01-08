<?php
    /**
     * Description of MetricOfAssessment
     *
     * @author stretch
     */
    class MetricOfAssessment extends N8_Model implements DataModelInterface {

        private $metric_id;
        private $unit_id;
        private $points;
        private $description;
        private $short_description;

        /**
         * class constructor, loads the data if $id (metric_id) is provided
         *
         * @param int $id
         */
        public function __construct($id = null) {
            parent::__construct();

            if(isset($id)) {
                $this->load($id);
            }
        }

        /**
         * loads the data from metric_of_assessment based on $this->metric_id
         *
         * @param int $id
         * @return void
         * @throws Exception
         */
        public function load($id) {
            $result = $this->db->get_where('metric_of_assessment', array('metric_id' => $id));

            $row = $result->row_array();
            if(!empty($row)) {
                foreach($row as $column => $value) {
                    if(property_exists($this, $column)) {
                        $this->$column = $value;
                    }
                }
            } else {
                throw new Exception('unable to load metric for '.$id);
            }
        }

        /**
         * saves the class to the db
         *
         * @return mixed
         */
        public function save() {
            $result = false;

            if(!$this->metric_id) {
                $this->insert();
                $result = $this->metric_id = $this->db->insert_id();
            } else {
                $result = $this->update();
            }

            return $result;
        }

        /**
         * updates an existing metric
         *
         * @return boolean
         */
        private function update() {
            $result = false;

            $sets = array();
            $sets["unit_id"]           = $this->unit_id;
            $sets["points"]            = $this->points;
            $sets["description"]       = $this->description;
            $sets["short_description"] = $this->short_description;

            if($this->db->where("metric_id", $this->metric_id)->update("metric_of_assessment", $sets)) {
                $result = true;
            }

            if($result === false) {
                $this->setError("there was a problem updating the metric_of_assessment table for ".$this->metric_id);
            }

            return $result;
        }

        /**
         * inserts data into the metric_of_assessment table
         *
         * @return void
         */
        private function insert() {
            $values = array();
            $values["unit_id"]           = $this->unit_id;
            $values["points"]            = $this->points;
            $values["description"]       = $this->description;
            $values["short_description"] = $this->short_description;

            $this->db->insert('metric_of_assessment', $values);
        }

        /**
         * gets the metric id
         *
         * @return int
         */
        public function getMetricId() {
            return $this->metric_id;
        }

        /**
         * gets the unit id
         *
         * @return int
         */
        public function getUnitId() {
            return $this->unit_id;
        }

        /**
         * gets the points
         *
         * @return int
         */
        public function getPoints() {
            return $this->points;
        }

        /**
         * gets the metric description
         *
         * @return string
         */
        public function getDescription() {
            return $this->description;
        }

        /**
         * gets the metric short description
         *
         * @return string
         */
        public function getShortDescription() {
            return $this->short_description;
        }

        /**
         * sets the metric id
         *
         * @param int $metric_id
         * @return \MetricOfAssessment
         */
        public function setMetricId($metric_id) {
            $this->metric_id = $metric_id;
            return $this;
        }

        /**
         * sets the unit id
         *
         * @param int $unit_id
         * @return \MetricOfAssessment
         */
        public function setUnitId($unit_id) {
            $this->unit_id = $unit_id;
            return $this;
        }

        /**
         * sets the points
         *
         * @param int $points
         * @return \MetricOfAssessment
         */
        public function setPoints($points) {
            $this->points = $points;
            return $this;
        }

        /**
         * sets the metric description
         *
         * @param string $description
         * @return \MetricOfAssessment
         */
        public function setDescription($description) {
            $this->description = $description;
            return $this;
        }

        /**
         * sets the metric short description
         *
         * @param string $short_description
         * @return \MetricOfAssessment
         */
        public function setShortDescription($short_description) {
            $this->short_description = $short_description;
            return $this;
        }
    }
