<?php
    /**
     * MembersIterator: loads and iterates through Member objects.
     *
     * @author stretch
     */
    class MembersIterator extends N8_Model implements Iterator {

        const PRIESTHOOD_OFFICE_ELDER       = 'ELDER';
        const PRIESTHOOD_OFFICE_HIGH_PRIEST = 'HIGH PRIEST';
        const PRIESTHOOD_OFFICE_PRIEST      = 'PRIEST';
        const ALL                           = 'ALL';

        private $position = 0;
        private $members_array = array();

        /**
         * class constructor
         *
         * @param int $unit_id
         */
        public function __construct($unit_id, $priesthood = null) {
            parent::__construct();

            if(!is_null($priesthood)) {
                $this->loadMembersByPriesthoodOffice($unit_id, $priesthood);
            } else {
                $this->loadMembersByUnit($unit_id);
            }
        }

        /**
         * loads members by priesthood offices, thus only loading priesthood holders
         *
         * @param int $unit_id
         * @param string $priesthood
         * @throws UnexpectedValueException
         */
        private function loadMembersByPriesthoodOffice($unit_id, $priesthood = 'ALL') {
            if(  is_numeric( $unit_id)) {
                $priesthood_offices = $this->getPriesthoodOffices($priesthood);

                $result = $this->db->select('member_id')
                                    ->from('members')
                                    ->where('unit_id', $unit_id)
                                    ->where_in('priesthood_office', $priesthood_offices)
                                    ->get();

                foreach($result->result_array as $row) {
                    //have the Member class load itself so we don't have to change the iterator
                    //every time a new field is added to members
                    $this->members_array[] = new Member($row['member_id']);
                }
            } else {
                throw new UnexpectedValueException(__METHOD__ . ' - invalid unit id provided');
            }
        }

        /**
         * returns an array of priesthood offices
         *
         * @param string $priesthood
         * @return array
         */
        private function getPriesthoodOffices($priesthood) {
            $priesthood_offices = array();
            switch($priesthood) {
                case self::PRIESTHOOD_OFFICE_PRIEST:
                    $priesthood_offices[] = self::PRIESTHOOD_OFFICE_PRIEST;
                    break;

                case self::PRIESTHOOD_OFFICE_ELDER:
                    $priesthood_offices[] = self::PRIESTHOOD_OFFICE_ELDER;
                    break;

                case self::PRIESTHOOD_OFFICE_HIGH_PRIEST:
                    $priesthood_offices[] = self::PRIESTHOOD_OFFICE_HIGH_PRIEST;
                    break;

                case self::ALL:
                default:
                    $priesthood_offices[] = self::PRIESTHOOD_OFFICE_PRIEST;
                    $priesthood_offices[] = self::PRIESTHOOD_OFFICE_ELDER;
                    $priesthood_offices[] = self::PRIESTHOOD_OFFICE_HIGH_PRIEST;
                    break;
            }

            return $priesthood_offices;
        }

        /**
         * loads the member data into Member objects
         *
         * @param int $unit_id
         * @throws UnexpectedValueException
         * @return void
         */
        private function loadMembersByUnit($unit_id) {
            if(  is_numeric( $unit_id)) {
                $result = $this->db->select('member_id')
                                    ->from('members')
                                    ->where('unit_id', $unit_id)
                                    ->get();

                foreach($result->result_array as $row) {
                    //have the Member class load itself so we don't have to change the iterator
                    //every time a new field is added to members
                    $this->members_array[] = new Member($row['member_id']);
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
            return $this->members_array[$this->position];
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
            return (isset($this->members_array[$this->position]) && $this->members_array[$this->position] instanceof Member);
        }
    }
