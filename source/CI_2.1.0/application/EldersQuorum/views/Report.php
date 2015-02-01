<?php
    require_once('templates/Base.php');

    /**
     * ReportVW: displays the reporting form and submission message
     *
     * @author stretch
     */
    class ReportVW extends Base {

        private $form;
        private $response;

        /**
         * class constructor
         */
        public function __construct() {
            parent::__construct();
        }

        /**
         * generates the reporting form
         *
         * @return void
         */
        protected function generateView() {
        ?>
            <div id="submit_message" class="error"><?= $this->response; ?></div>
        <?
            $this->form->renderForm();
        }

        /**
         * sets the form property
         *
         * @param Form $form
         * @return \ReportVW
         */
        public function setForm(Form $form) {
            $this->form = $form;
            return $this;
        }

        /**
         * sets the response property
         *
         * @param string $response
         * @return \ReportVW
         */
        public function setResponse($response) {
            $this->response = $response;
            return $this;
        }
    }
