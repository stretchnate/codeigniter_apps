<?php
    require_once(APPPATH.'/views/budget/baseVW.php');

    /**
     * Description of ContentVW
     *
     * @author stretch
     */
    class ContentVW extends Budget_BaseVW {

        private $content = array();

        public function __construct($ci) {
            parent::__construct($ci);
        }

        protected function generateView() {
            foreach($this->content as $content) {
                echo $content->getContent();
            }
        }

        public function setContent(SiteContent_Content $content) {
            $this->content[] = $content;
            return $this;
        }

    }
