<?php

    /**
     * Content: models a row in SITE_CONTENT.CONTENT
     *
     * @author stretch
     */
    class SiteContent_Content {
        private $site_id;
        private $content_id;
        private $content;
        private $content_description;
        private $start_time;
        private $end_time;
        private $revised_id;
        private $revised_date;

        /**
         * set the site id
         *
         * @param mixed $site_id
         * @return \SiteContent_Content
         */
        public function setSiteId($site_id) {
            $this->site_id = $site_id;
            return $this;
        }

        /**
         * set the content id
         *
         * @param int $content_id
         * @return \SiteContent_Content
         */
        public function setContentId($content_id) {
            $this->content_id = $content_id;
            return $this;
        }

        /**
         * set the content
         *
         * @param string $content
         * @return \SiteContent_Content
         */
        public function setContent($content) {
            $this->content = $content;
            return $this;
        }

        /**
         * set the content description
         *
         * @param string $content_description
         * @return \SiteContent_Content
         */
        public function setContentDescription($content_description) {
            $this->content_description = $content_description;
            return $this;
        }

        /**
         * set the start time
         *
         * @param string $start_time
         * @return \SiteContent_Content
         */
        public function setStartTime($start_time) {
            $this->start_time = $start_time;
            return $this;
        }

        /**
         * set the end time
         *
         * @param string $end_time
         * @return \SiteContent_Content
         */
        public function setEndTime($end_time) {
            $this->end_time = $end_time;
            return $this;
        }

        /**
         * set the revised id
         *
         * @param string $revised_id
         * @return \SiteContent_Content
         */
        public function setRevisedId($revised_id) {
            $this->revised_id = $revised_id;
            return $this;
        }

        /**
         * set the revised date
         *
         * @param string $revised_date
         * @return \SiteContent_Content
         */
        public function setRevisedDate($revised_date) {
            $this->revised_date = $revised_date;
            return $this;
        }

        /**
         * get the site id
         *
         * @return mixed
         */
        public function getSiteId() {
            return $this->site_id;
        }

        /**
         * get the content id
         *
         * @return int
         */
        public function getContentId() {
            return $this->content_id;
        }

        /**
         * get the content
         *
         * @return string
         */
        public function getContent() {
            return $this->content;
        }

        /**
         * get the content description
         *
         * @return string
         */
        public function getContentDescription() {
            return $this->content_description;
        }

        /**
         * get the start time
         *
         * @return string
         */
        public function getStartTime() {
            return $this->start_time;
        }

        /**
         * get the end time
         *
         * @return string
         */
        public function getEndTime() {
            return $this->end_time;
        }

        /**
         * get the revised id
         *
         * @return string
         */
        public function getRevisedId() {
            return $this->revised_id;
        }

        /**
         * get the revised date
         *
         * @return string
         */
        public function getRevisedDate() {
            return $this->revised_date;
        }
    }
