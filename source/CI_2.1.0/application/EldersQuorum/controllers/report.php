<?php
    /**
     * Description of report
     *
     * @author stretch
     */
    class report extends N8_Controller {

        private $form;
        private $metrics_iterator;
        private $home_teachers_iterator;
        private $members_iterator;

        public function __construct() {
            parent::__construct();
            $this->load->view('Report');
            $this->view = new ReportVW();
        }

        public function index() {
            $this->metrics_iterator = new MetricOfAssessmentIterator(1);
            $this->home_teachers_iterator = new MembersIterator(1, MembersIterator::ALL);
            $this->members_iterator = new MembersIterator(1);

            $this->buildReportForm();

            $this->view->setForm($this->form);
            $this->view->renderView();
        }

        /**
         * builds the reporting form
         *
         * @return void
         */
        private function buildReportForm() {
            $this->startForm('report_form');
            $this->form->addField($this->buildHomeTeachersDropdown());
            $this->form->addField($this->buildFamilyDropdown());
            $this->buildReportRadioGroup();
            $this->form->addField($this->buildReportConcernsTextBox());
            $this->form->addField($this->buildFormSubmitButton('Submit Report'));
        }

        /**
         * builds the event submit button
         *
         * @access private
         * @return \Form_Field_Input_Button
         */
        private function buildFormSubmitButton($value) {
            $field = new Form_Field_Input_Button();
            $field->setId('submit_report')
                    ->setValue($value);

            return $field;
        }

        private function buildReportConcernsTextBox() {
            $field = new Form_Field_Input_Textarea();
            $field->setName('concerns')
                    ->setId('concerns')
                    ->setRows(10)
                    ->setCols(75)
                    ->setContainerClass('form_field form_field_textarea')
                    ->setLabelContainerClass('')
                    ->setFieldContainerClass('')
                    ->setLabel('Notes regarding the family home taught');

            return $field;
        }

        /**
         * builds the group of radio buttons used to report home teaching activities
         *
         * @return void
         */
        private function buildReportRadioGroup() {
            $this->form->addField($this->buildContactLabel());
            while($this->metrics_iterator->valid()) {
                $metric = $this->metrics_iterator->current();
                $field = new Form_Field_Input_Checkbox_Radio();
                $field->setName('assessment')
                        ->setId('assessment_'.$metric->getPoints())
                        ->setValue($metric->getPoints())
                        ->setLabel($metric->getShortDescription())
                        ->setLabelClass('assessment_label')
                        ->setLabelPlacement(Form_Field::LABEL_PLACEMENT_RIGHT);

                $this->form->addField($field);

                $this->metrics_iterator->next();
            }
        }

        private function buildContactLabel() {
            $label = new Form_Field_Label();
            $label->setContent('Visit/Contact Type:')
                    ->setId('contact_label');

            return $label;
        }

        /**
         * builds the families dropdown field
         *
         * @param int $selected_option
         * @return \Form_Field_Select
         */
        private function buildFamilyDropdown($selected_option = null) {
            $field = new Form_Field_Select();
            $field->setName('family')
                    ->setId('family')
                    ->addOption("", "-- Family --");

            while($this->members_iterator->valid()) {
                $family = $this->home_teachers_iterator->current();
                $family_name = $family->getLastName() . ', ' . $family->getFirstName();
                $field->addOption($family->getMemberId(), $family_name);

                $this->members_iterator->next();
            }

            $field->setSelectedOption($selected_option);

            return $field;
        }

        /**
         * builds the home teachers dropdown field
         *
         * @param int $selected_option
         * @return \Form_Field_Select
         */
        private function buildHomeTeachersDropdown($selected_option = null) {
            $field = new Form_Field_Select();
            $field->setName('home_teachers')
                    ->setId('home_teachers')
                    ->addOption("", "-- Home Teacher --");

            while($this->home_teachers_iterator->valid()) {
                $home_teacher = $this->home_teachers_iterator->current();
                $ht_name = $home_teacher->getLastName() . ', ' . $home_teacher->getFirstName();
                $field->addOption($home_teacher->getMemberId(), $ht_name);

                $this->home_teachers_iterator->next();
            }

            $field->setSelectedOption($selected_option);

            return $field;
        }

        private function startForm($form_id) {
            $this->form = new Form();
            $this->form->setAction('')
                ->setMethod('post')
                ->setId($form_id);
        }
    }
