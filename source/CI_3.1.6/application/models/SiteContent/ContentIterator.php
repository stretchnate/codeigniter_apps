<?php

    /**
     * SiteContent_ContentIterator: fetches data from SITE_CONTENT.CONTENT and iterates through it
     *
     * @author stretch
     */
    class SiteContent_ContentIterator extends SiteContent_BaseIterator {

        protected $site_id = 'mon';

        public function __construct($content_qualifier = null) {
            parent::__construct('SiteContent_Content');

            if($content_qualifier) {
                $this->fetchContentByQualifier($content_qualifier);
            }
        }

        /**
         * get content from SITE_CONTENT.CONTENT based on content_id
         *
         * @param int $content_id
         * @return void
         */
        public function fetchContentByQualifier($content_qualifier) {
            $where = array(
                'site_id' => $this->site_id,
                'qualifier' => $content_qualifier
            );

            $content = $this->content_db->get_where('CONTENT', $where);

            $this->populate($content->result_array());
        }

        /**
         * populate the items_array
         *
         * @param array $rows
         * @return void
         */
        public function populate(array $rows) {
            foreach($rows as $row) {
                $content = new SiteContent_Content();
                $content->setSiteId($row['site_id'])
                        ->setContentId($row['content_id'])
                        ->setContent($row['content'])
                        ->setContentDescription($row['content_description'])
                        ->setStartTime($row['start_time'])
                        ->setEndTime($row['end_time'])
                        ->setRevisedId($row['revised_id'])
                        ->setRevisedDate($row['revised_date']);

                $this->items_array[] = $content;
            }
        }
    }
