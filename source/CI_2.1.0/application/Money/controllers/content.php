<?php
    /**
     * content
     *
     * @author stretch
     */
    class content extends N8_Controller {

        public function __construct() {
            parent::__construct();
            $this->load->view('ContentVW');
            $this->view = new ContentVW(get_instance());
        }

        public function index() {
            redirect('/content/about');
        }

        public function about() {
            $content_iterator = new SiteContent_ContentIterator('ABOUT');

            while($content_iterator->valid()) {
                $this->view->setContent($content_iterator->current());
                $content_iterator->next();
            }

            $this->view->renderView();
        }

        public function how_it_works() {
            $content_iterator = new SiteContent_ContentIterator('HOW_IT_WORKS');

            while($content_iterator->valid()) {
                $this->view->setContent($content_iterator->current());
                $content_iterator->next();
            }

            $this->view->renderView();
        }
    }
