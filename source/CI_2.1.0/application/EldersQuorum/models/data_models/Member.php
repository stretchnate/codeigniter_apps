<?php
    /**
     * Member: representation of any single row in the Members table.
     *
     * @author stretch
     */
    class Member extends N8_Model implements DataModelInterface {

        private $member_id;
        private $church_record_id;
        private $last_name;
        private $first_name;
        private $middle_initial;
        private $priesthood_office;
        private $assigned_quorum;
        private $unit_id;

        /**
         * class constructor: if $id is provided this will load the member data into the class properties.
         *
         * @param int $id
         * @return void
         */
        public function __construct($id = null) {
            parent::__construct();

            if($id) {
                $this->load($id);
            }
        }

        /**
         * loads an existing members data into the class properites.
         *
         * @param int $member_id
         * @throws Exception
         * @return void
         */
        public function load($member_id) {
            $result = $this->db->get_where('members', array('member_id' => $member_id));

            $rows = $result->row_array();
            if(!empty($rows)) {
                foreach($rows as $column => $value) {
                    if(property_exists($this, $column)) {
                        $this->$column = $value;
                    }
                }
            } else {
                throw new Exception('unable to load member with id of '.$id);
            }
        }

        /**
         * saves the class to the db
         *
         * @return mixed
         */
        public function save() {
            $result = false;

            if(!$this->member_id) {
                $this->insert();
                $result = $this->member_id = $this->db->insert_id();
            } else {
                $result = $this->update();
            }

            return $result;
        }

        /**
         * updates an existing member
         *
         * @return boolean
         */
        private function update() {
            $result = false;

            $sets = array();
            $sets['member_id']         = $this->member_id;
            $sets['church_record_id']  = $this->church_record_id;
            $sets['last_name']         = $this->last_name;
            $sets['first_name']        = $this->first_name;
            $sets['middle_initial']    = $this->middle_initial;
            $sets['priesthood_office'] = $this->priesthood_office;
            $sets['assigned_quorum']   = $this->assigned_quorum;
            $sets['unit_id']           = $this->unit_id;

            if($this->db->where("member_id", $this->member_id)->update("members", $sets)) {
                $result = true;
            }

            if($result === false) {
                $this->setError("there was a problem updating the member table for ".$this->first_name);
            }

            return $result;
        }

        /**
         * inserts data into the members table
         *
         * @return void
         */
        private function insert() {
            $values = array();
            $values['member_id']         = $this->member_id;
            $values['church_record_id']  = $this->church_record_id;
            $values['last_name']         = $this->last_name;
            $values['first_name']        = $this->first_name;
            $values['middle_initial']    = $this->middle_initial;
            $values['priesthood_office'] = $this->priesthood_office;
            $values['assigned_quorum']   = $this->assigned_quorum;
            $values['unit_id']           = $this->unit_id;

            $this->db->insert('members', $values);
        }

        /**
         * returns the member id
         *
         * @return int
         */
        public function getMemberId() {
            return $this->member_id;
        }

        /**
         * returns the church record id
         *
         * @return int
         */
        public function getChurchRecordId() {
            return $this->church_record_id;
        }

        /**
         * returns the last name
         *
         * @return string
         */
        public function getLastName() {
            return $this->last_name;
        }

        /**
         * returns the first name
         *
         * @return string
         */
        public function getFirstName() {
            return $this->first_name;
        }

        /**
         * returns the middle initial
         *
         * @return string
         */
        public function getMiddleInitial() {
            return $this->middle_initial;
        }

        /**
         * returns the priesthood office
         *
         * @return string
         */
        public function getPriesthoodOffice() {
            return $this->priesthood_office;
        }

        /**
         * returns the quorum that the home teachers are assigned from
         *
         * @return string
         */
        public function getAssignedQuorum() {
            return $this->assigned_quorum;
        }

        /**
         * returns the unit id per the units table
         *
         * @return int
         */
        public function getUnitId() {
            return $this->unit_id;
        }

        /**
         * sets the member id
         *
         * @param int $member_id
         * @return \Member
         */
        public function setMemberId($member_id) {
            $this->member_id = $member_id;
            return $this;
        }

        /**
         * sets the church record id
         *
         * @param int $church_record_id
         * @return \Member
         */
        public function setChurchRecordId($church_record_id) {
            $this->church_record_id = $church_record_id;
            return $this;
        }

        /**
         * sets the last_name
         *
         * @param string $last_name
         * @return \Member
         */
        public function setLastName($last_name) {
            $this->last_name = $last_name;
            return $this;
        }

        /**
         * sets the first_name
         *
         * @param string $first_name
         * @return \Member
         */
        public function setFirstName($first_name) {
            $this->first_name = $first_name;
            return $this;
        }

        /**
         * sets the middle initial
         *
         * @param string $middle_initial
         * @return \Member
         */
        public function setMiddleInitial($middle_initial) {
            $this->middle_initial = $middle_initial;
            return $this;
        }

        /**
         * sets the priesthood office
         *
         * @param string $priesthood_office
         * @return \Member
         */
        public function setPriesthoodOffice($priesthood_office) {
            $this->priesthood_office = $priesthood_office;
            return $this;
        }

        /**
         * sets the assigned quorum
         *
         * @param string $assigned_quorum
         * @return \Member
         */
        public function setAssignedQuorum($assigned_quorum) {
            $this->assigned_quorum = $assigned_quorum;
            return $this;
        }

        /**
         * sets the unit id
         *
         * @param int $unit_id
         * @return \Member
         */
        public function setUnitId($unit_id) {
            $this->unit_id = $unit_id;
            return $this;
        }
    }
