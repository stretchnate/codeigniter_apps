<?php
    require_once('templates/Base.php');

    /**
     * Description of ReportVW
     *
     * @author stretch
     */
    class ReportVW extends Base {

        private $form;

        public function __construct() {
            parent::__construct();
        }

        protected function generateView() {
            $this->form->renderForm();
        }

        public function setForm(Form $form) {
            $this->form = $form;
        }
    }
