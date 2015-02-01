<?php
    /**
     * Description of home
     *
     * @author stretch
     */
    class home extends N8_Controller {

        public function __construct() {
			parent::__construct();
			$this->load->view('Home');
            //@todo de-hardcode the unit id used for MetricOfAssessmentIterator()
            $metrics = new MetricOfAssessmentIterator(1);

			$this->view = new HomeVW($metrics);
		}

		public function index() {
			$this->view->renderView();
		}
    }
