<?php
    /**
     * HTReportingIterator: iterates through the HTMonthlyReporting objects
     *
     * @author stretch
     */
    class HTMonthlyReportingIterator extends BaseIterator {

        private $home_teacher_id;
        private $start_month;
        private $end_month;
        private $family_id;

        public function __construct($start_month, $end_month = null, $home_teacher_id = null, $family_id = null) {
            parent::__construct('HTMonthlyReporting');

            $this->start_month     = $start_month;
            $this->end_month       = isset($end_month) ? $end_month : date('m');
            $this->home_teacher_id = $home_teacher_id;
            $this->family_id       = $family_id;

            $this->load();
        }

        /**
         * loads the items array
         *
         * @return void
         */
        protected function load() {
            try {
                $report_array = $this->getHomeTeachingReportArray();

                foreach($report_array as $row) {
                    $ht_report = new HTMonthlyReporting();
                    $ht_report->setHomeTeacher($row['home_teacher_id'])
                            ->setFamily($row['family_id'])
                            ->setDateOfVisit($row['date_of_visit'])
                            ->setContactAssessment($row['contact_assessment_value'])
                            ->setConcerns($row['concerns']);

                    $this->items_array[] = $ht_report;
                }
            } catch (Exception $e) {
                //need to log an error here.
            }
        }

        /**
         * fetches the home teaching report
         *
         * @return array
         */
        private function getHomeTeachingReportArray() {
            $where = "DATE_FORMAT(date_of_visit, '%m') BETWEEN {$this->start_month} AND {$this->end_month}";

            if(isset($this->home_teacher_id)) {
                $where .= " AND home_teacher_id = {$this->home_teacher_id}";
            }

            if(isset($this->family_id)) {
                $where .= " AND family_id = {$this->family_id}";
            }

            $results = $this->db->select()
                    ->from('ht_monthly_reporting')
                    ->where($where)
                    ->get();

            return $results->result_array();
        }
    }
