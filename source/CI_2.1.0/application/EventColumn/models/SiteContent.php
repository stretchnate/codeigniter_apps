<?php
    /**
     * Description of SiteContent
     *
     * @author stretch
     */
    abstract class SiteContent extends N8_Model {

        protected $ci;

        public function __construct() {
            parent::__construct();
            $this->ci =& get_instance();
            $this->content_db = $this->ci->load->database('content', true, true);
        }

        abstract protected function populate(array $result);
    }
