<?php
	/**
	 * Description of search
	 *
	 * @author stretch
	 */
	class search extends N8_Controller {

		public function __construct() {
			parent::__construct();

			$this->load->view('Search');
			$this->view = new SearchVW();
			$this->generateCategoriesNav();
		}

		public function advanced($cache_key = null) {
			if($cache_key) {
				$cache_util = new CacheUtil();
				$cache_array = $cache_util->fetchCache($cache_key);
			} else {
				$cache_array = array();
				$cache_array['event_title']['value'] = null;
				$cache_array['event_title']['error'] = null;
				$cache_array['city']['value'] = null;
				$cache_array['city']['error'] = null;
				$cache_array['state']['value'] = null;
				$cache_array['state']['error'] = null;
				$cache_array['zip']['value'] = null;
				$cache_array['zip']['error'] = null;
				$cache_array['start_date']['value'] = null;
				$cache_array['start_date']['error'] = null;
				$cache_array['end_date']['value'] = null;
				$cache_array['end_date']['error'] = null;
			}

			try {
				$form = new Form();
				$form->setAction('map/search');
				$form->addHiddenInput('search_type', 'advanced_search');

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setLabel( "Event Title" );
				$field->setValue( $cache_array[$field->getName()]['value'] );
				$field->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setLabel( "City" );
				$field->setValue( $cache_array[$field->getName()]['value'] );
				$field->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setLabel( "State" );
				$field->setValue( $cache_array[$field->getName()]['value'] );
				$field->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setLabel( "Zip" );
				$field->setValue( $cache_array[$field->getName()]['value'] );
				$field->setMaxLength(5);
				$field->setSize(5);
				$field->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setLabel( "Start Date" );
				$field->setSize(10);
				$field->setValue( $cache_array[$field->getName()]['value'] );
				$field->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setLabel( "End Date" );
				$field->setSize(10);
				$field->setValue( $cache_array[$field->getName()]['value'] );
				$field->addErrorLabel( 'error', null, $cache_array[$field->getName()]['error'] );

				$form->addField($field);

				$field = Form::getNewField( Form_Field::FIELD_TYPE_SUBMIT );
				$field->setId( "submit" );
				$field->setValue( "Change this to a button and add Javascript" );

				$form->addField( $field );

				$this->view->setErrors( $this->getErrors() );
				$this->view->setSearchForm( $form );
				$this->view->setPageId('advanced_search');

				$this->view->renderView();
			} catch(Exception $e) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}
	}

?>
