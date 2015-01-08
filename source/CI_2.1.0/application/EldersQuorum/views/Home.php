<?php
    require_once('templates/Base.php');

    /**
     * Description of Home
     *
     * @author stretch
     */
    class HomeVW extends Base {

        protected $metrics_iterator;

        public function __construct(MetricOfAssessmentIterator $metrics_iterator) {
            parent::__construct();
            $this->metrics_iterator = $metrics_iterator;
            $this->metrics_iterator->rewind();
        }

        protected function generateView() {
            $list = array();
            $i = $this->metrics_iterator->count() - 1;

            while($this->metrics_iterator->valid()) {
                $list[] = $i . ' - ' .$this->metrics_iterator->current()->getDescription();
                $this->metrics_iterator->next();
                $i--;
            }

            echo ul($list);

            echo "<p><a href='/report'>Report Home Teaching</a></p>";
        }
    }
