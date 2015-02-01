<?php
    /**
     * HTReporting: models row data in ht_monthly_reporting table
     *
     * @author stretch
     */
    class HTMonthlyReporting extends N8_Model implements DataModelInterface {

        private $home_teacher; //Member object
        private $family; //Member object
        private $contact_assesment; //MetricOfAssessment object
        private $concerns;
        private $date_of_visit; //DateTimeObject

        public function __construct() {
            parent::__construct();

            $this->home_teacher      = new Member();
            $this->family            = new Member();
            $this->contact_assesment = new MetricOfAssessment;
        }

        /**
         * load the data into the model
         *
         * @param int $home_teacher_id
         * @param int $family_id
         * @param int $metric_of_assessment_value
         * @param string $concerns
         * @param string $date_of_visit
         * @return void
         */
        public function load($home_teacher_id, $family_id, $metric_of_assessment_value, $concerns, $date_of_visit) {
            $this->home_teacher->load($home_teacher_id);
            $this->family->load($family_id);
            $this->contact_assesment->loadByPoints($metric_of_assessment_value, $this->family->getUnitId());
            $this->concerns = $concerns;

            $this->date_of_visit = new DateTime($date_of_visit);
        }

        /**
         * save the data to ht_monthly_reporting
         *
         * @return boolean
         */
        public function save() {
            $values = array();
            $values["home_teacher_id"]          = $this->home_teacher->getMemberId();
            $values["family_id"]                = $this->family->getMemberId();
            $values["contact_assessment_value"] = $this->contact_assesment->getPoints();
            $values["concerns"]                 = $this->concerns;
            $values["date_of_visit"]            = $this->date_of_visit->format('Y-m-d');

            return $this->db->insert('ht_monthly_reporting', $values);
        }

        /**
         * get home teacher id
         *
         * @return int
         */
        public function getHomeTeacherId() {
            return $this->home_teacher->getMemberId();
        }

        /**
         * get family id
         *
         * @return int
         */
        public function getFamilyId() {
            return $this->family->getMemberId();
        }

        /**
         * get contact assessment value
         *
         * @return int
         */
        public function getContactAssessmentValue() {
            return $this->contact_assesment->getPoints();
        }

        /**
         * get concerns
         *
         * @return string
         */
        public function getConcerns() {
            return $this->concerns;
        }

        /**
         * get date of visit
         *
         * @return string
         */
        public function getDateOfVisit() {
            return $this->date_of_visit;
        }

        /**
         * get home teacher object
         *
         * @return \Member
         */
        public function getHomeTeacherObject() {
            return $this->home_teacher;
        }

        /**
         * get family object
         *
         * @return \Member
         */
        public function getFamilyObject() {
            return $this->family;
        }

        /**
         * get contact assessment object
         *
         * @return \MetricOfAssessment
         */
        public function getContactAssessmentObject() {
            return $this->contact_assesment;
        }

        /**
         * set the home teacher
         *
         * @param Member/int $home_teacher
         * @return \HTMonthlyReporting
         */
        public function setHomeTeacher($home_teacher) {
            if($home_teacher instanceof Member) {
                $this->home_teacher = $home_teacher;
            } else {
                $this->home_teacher->load($home_teacher);
            }

            return $this;
        }

        /**
         * set the family object
         *
         * @param Member/int $family
         * @return \HTMonthlyReporting
         */
        public function setFamily($family) {
            if($family instanceof Member) {
                $this->family = $family;
            } else {
                $this->family->load($family);
            }

            return $this;
        }

        /**
         * set contact of assessment object
         *
         * @param MetricOfAssessment/int $contact_assessment
         * @return \HTMonthlyReporting
         * @throws Exception
         */
        public function setContactAssessment($contact_assessment) {
            if($contact_assessment instanceof MetricOfAssessment) {
                $this->contact_assesment = $contact_assessment;
            } else {
                $unit_id = $this->family->getUnitId();
                if(empty($unit_id)) {
                    $unit_id = $this->home_teacher->getUnitId();
                }

                if(!empty($unit_id)) {
                    $this->contact_assesment->loadByPoints($contact_assessment, $unit_id);
                } else {
                    throw new Exception("must set family or home teacher before setting contact assessment");
                }
            }

            return $this;
        }

        /**
         * set concerns property
         *
         * @param string $concerns
         * @return \HTMonthlyReporting
         */
        public function setConcerns($concerns) {
            $this->concerns = $concerns;
            return $this;
        }

        /**
         * sets the date of visit property
         *
         * @param string $date_of_visit
         * @return \HTMonthlyReporting
         */
        public function setDateOfVisit($date_of_visit) {
            if($date_of_visit instanceof DateTime) {
                $this->date_of_visit = $date_of_visit;
            } else {
                if(!is_numeric( $date_of_visit )) {
                    $date_of_visit = strtotime($date_of_visit);
                }

                $this->date_of_visit = new DateTime($date_of_visit);
            }

            return $this;
        }
    }
