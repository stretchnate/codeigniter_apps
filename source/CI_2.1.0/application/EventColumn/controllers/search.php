<?php
	/**
	 * this class is for advanced search methods
	 *
	 * @author stretch
	 */
	class search extends N8_Controller {

        private $cache_array = array();
        private $form;

		public function __construct() {
			parent::__construct();

            $this->load->view('Search');
            $this->view = new SearchVW();

//            try {
//                $this->generateCategoriesNav();
//            } catch(Exception $e) {
//                $this->logMessage( $e->getMessage(), N8_Error::ERROR );
//            }
		}

        /**
         * generate the advanced search form
         *
         * @param string $cache_key
         */
		public function advanced($cache_key = null) {
            try {
                $this->cache_array = $this->getCache($cache_key);
                $this->startForm();
				$this->form->addField($this->buildEventTitleField());
                $this->form->addField($this->buildCityField());
                $this->form->addField($this->buildStateField());
                $this->form->addField($this->buildZipField());
                $this->form->addField($this->buildFromDateField());
                $this->form->addField($this->buildToDateField());
                $this->form->addField($this->buildSubmitButton());

				$this->view->setErrors( $this->getErrors() );
				$this->view->setSearchForm( $this->form );
				$this->view->setPageId('advanced_search');

				$this->view->renderView();
			} catch(Exception $e) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}

        /**
         * builds the submit button for the form
         *
         * @return Form_Field_Input_Button
         */
        private function buildSubmitButton() {
            $field = new Form_Field_Input_Button();
            $field->setId( "search_submit" );
            $field->setContent( 'Search' );

            return $field;
        }

        /**
         * builds the To date field
         *
         * @return Form_Field_Input
         */
        private function buildToDateField() {
            $field = new Form_Field_Input();
            $field->setName( "to_date" )
                  ->setId('to_date')
                  ->setSize(10)
                  ->setValue( $this->cache_array[$field->getName()]['value'] )
                  ->setClass('toggle_text form_text hasDatePicker')
                  ->addErrorLabel( 'error', null, $this->cache_array[$field->getName()]['error'] );

            return $field;
        }

        /**
         * builds the From date field
         *
         * @return Form_Field_Input
         */
        private function buildFromDateField() {
            $field = new Form_Field_Input();
            $field->setName( "from_date" )
                  ->setId('from_date')
                  ->setSize(10)
                  ->setValue( $this->cache_array[$field->getName()]['value'] )
                  ->setClass('toggle_text form_text hasDatePicker')
                  ->addErrorLabel( 'error', null, $this->cache_array[$field->getName()]['error'] );

            return $field;
        }

        /**
         * builds the zip field
         *
         * @return Form_Field_Input
         */
        private function buildZipField() {
            $field = new Form_Field_Input();
            $field->setName( "zip" )
                  ->setId('zip')
                  ->setValue( $this->cache_array[$field->getName()]['value'] )
                  ->setMaxLength(5)
                  ->setSize(5)
                  ->setClass('toggle_text form_text')
                  ->addErrorLabel( 'error', null, $this->cache_array[$field->getName()]['error'] );

            return $field;
        }

        /**
         * builds the state dropdown field
         *
         * @return Form_Field_Select
         */
        private function buildStateField() {
            $state_iterator = new StateIterator();
            $field = new Form_Field_Select();
            $field->setName("state")
                  ->setId('state')
                  ->setClass('form_text')
                  ->addOption('', '-- Choose State --')
                  ->addErrorLabel('error', null, $this->cache_array[$field->getName()]['error']);

            while($state_iterator->valid()) {
                $field->addOption($state_iterator->current()->getStateCode(), $state_iterator->current()->getStateName());
                $state_iterator->next();
            }

            return $field;
        }

        /**
         * builds the city field
         *
         * @return Form_Field_Input
         */
        private function buildCityField() {
            $field = new Form_Field_Input();
            $field->setName( "city" )
                  ->setId('city')
                  ->setValue( $this->cache_array[$field->getName()]['value'] )
                  ->setClass('toggle_text form_text')
                  ->addErrorLabel( 'error', null, $this->cache_array[$field->getName()]['error'] );

            return $field;
        }

        /**
         * builds the event title field
         *
         * @return Form_Field_Input
         */
        private function buildEventTitleField() {
            $field = new Form_Field_Input();
            $field->setName('event_title')
                  ->setId('event_title')
                  ->setValue( $this->cache_array[$field->getName()]['value'] )
                  ->setClass('toggle_text form_text')
                  ->addErrorLabel( 'error', null, $this->cache_array[$field->getName()]['error'] );

            return $field;
        }

        /**
         * starts the form, sets $this->form to a Form object
         */
        private function startForm() {
            $this->form = new Form();
            $this->form->setAction('map/search');
            $this->form->addHiddenInput('search_type', 'advanced_search');
            $this->form->setName('advanced_search');
        }

        /**
         * fetches cached data if any, if not generates a default array
         *
         * @param string $cache_key
         */
        private function getCache($cache_key) {
            if($cache_key) {
                $cache_util = new CacheUtil();
                $cache_array = $cache_util->fetchCache($cache_key);
            }

            if(empty($cache_array)) {
                $cache_array = array();
                $cache_array['event_title']['value'] = 'Event Title';
                $cache_array['event_title']['error'] = null;
                $cache_array['city']['value'] = 'City';
                $cache_array['city']['error'] = null;
                $cache_array['state']['value'] = 'State';
                $cache_array['state']['error'] = null;
                $cache_array['zip']['value'] = 'Zip';
                $cache_array['zip']['error'] = null;
                $cache_array['from_date']['value'] = 'From Date';
                $cache_array['from_date']['error'] = null;
                $cache_array['to_date']['value'] = 'To Date';
                $cache_array['to_date']['error'] = null;
            }

            $this->cache_array = $cache_array;
        }
    }

?>
