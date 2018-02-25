<?php
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     * Description of SiteContent
     *
     * @author stretch
     */
    abstract class SiteContent extends N8_Model {

        public function __construct() {
            parent::__construct();

            $this->content_db = $this->load->database('content', true);
        }
    }
