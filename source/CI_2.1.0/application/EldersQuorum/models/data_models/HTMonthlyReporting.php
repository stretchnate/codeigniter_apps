<?php
    /**
     * HTReporting
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

        public function load($home_teacher_id, $family_id, $metric_of_assessment_value, $concerns, $date_of_visit) {
            $this->home_teacher->load($home_teacher_id);
            $this->family->load($family_id);
            $this->contact_assesment->loadByPoints($metric_of_assessment_value, $this->family->getUnitId());
            $this->concerns = $concerns;

            $this->date_of_visit = new DateTime($date_of_visit);
        }

        public function save() {
            $values = array();
            $values["home_teacher_id"]          = $this->home_teacher->getMemberId();
            $values["family_id"]                = $this->family->getMemberId();
            $values["contact_assessment_value"] = $this->contact_assesment->getPoints();
            $values["concerns"]                 = $this->concerns;
            $values["date_of_visit"]            = $this->date_of_visit->format('Y-m-d');

            $this->db->insert('ht_monthly_reporting', $values);
            return $this->db->insert_id();
        }

        public function getHomeTeacherId() {
            return $this->home_teacher->getMemberId();
        }

        public function getFamilyId() {
            return $this->family->getMemberId();
        }

        public function getContactAssessmentValue() {
            return $this->contact_assesment->getPoints();
        }

        public function getConcerns() {
            return $this->concerns;
        }

        public function getDateOfVisit() {
            return $this->date_of_visit;
        }

        public function getHomeTeacherObject() {
            return $this->home_teacher;
        }

        public function getFamilyObject() {
            return $this->family;
        }

        public function getContactAssessmentObject() {
            return $this->contact_assesment;
        }

        public function setHomeTeacher($home_teacher) {
            if($home_teacher instanceof Member) {
                $this->home_teacher = $home_teacher;
            } else {
                $this->home_teacher->load($home_teacher);
            }

            return $this;
        }

        public function setFamily($family) {
            if($family instanceof Member) {
                $this->family = $family;
            } else {
                $this->family->load($family);
            }

            return $this;
        }

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

        public function setConcerns($concerns) {
            $this->concerns = $concerns;
            return $this;
        }

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
