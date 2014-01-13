<?php
	/**
	 * Description of search
	 *
	 * @author stretch
	 */
	class search extends N8_Controller {

		public function advanced($cache_key = null) {
			if($cache_key) {
				$cache_array = $this->fetchCache($cache_key);
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
				$this->load->view('Search');

				$form = new Form();
				$form->setAction('map/search');
				$form->addHiddenInput('search_type', 'advanced_search');

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setContainerClass( "form-field" );
				$field->setLabel( "Event Title" );
				$field->setLabelContainerClass( "form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "event_title" );
				$field->setName( "event_title" );
				$field->setValue( $cache_array['event_title']['value'] );
				$field->addErrorLabel( 'error', null, $cache_array['event_title']['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setContainerClass( "form-field" );
				$field->setLabel( "City" );
				$field->setLabelContainerClass( "form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "city" );
				$field->setName( "city" );
				$field->setValue( $cache_array['city']['value'] );
				$field->addErrorLabel( 'error', null, $cache_array['city']['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setContainerClass( "form-field" );
				$field->setLabel( "State" );
				$field->setLabelContainerClass( "form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "state" );
				$field->setName( "state" );
				$field->setValue( $cache_array['state']['value'] );
				$field->addErrorLabel( 'error', null, $cache_array['state']['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setContainerClass( "form-field" );
				$field->setLabel( "Zip" );
				$field->setLabelContainerClass( "form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "zip" );
				$field->setName( "zip" );
				$field->setValue( $cache_array['zip']['value'] );
				$field->setMaxLength(5);
				$field->setSize(5);
				$field->addErrorLabel( 'error', null, $cache_array['zip']['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setContainerClass( "form-field" );
				$field->setLabel( "Start Date" );
				$field->setLabelContainerClass( "form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "start_date" );
				$field->setName( "start_date" );
				$field->setSize(10);
				$field->setValue( $cache_array['start_date']['value'] );
				$field->addErrorLabel( 'error', null, $cache_array['start_date']['error'] );

				$form->addField($field);

				$field = Form::getNewField(Form_Field::FIELD_TYPE_INPUT);
				$field->setContainerClass( "form-field" );
				$field->setLabel( "End Date" );
				$field->setLabelContainerClass( "form-label" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "end_date" );
				$field->setName( "end_date" );
				$field->setSize(10);
				$field->setValue( $cache_array['end_date']['value'] );
				$field->addErrorLabel( 'error', null, $cache_array['end_date']['error'] );

				$form->addField($field);

				$field = Form::getNewField( Form_Field::FIELD_TYPE_SUBMIT );
				$field->setContainerClass( "form-field" );
				$field->setFieldContainerClass( "field-container" );
				$field->setId( "submit" );
				$field->setValue( "Change this to a button and add Javascript" );

				$form->addField( $field );

				$view = new SearchVW();
				$view->setErrors( $this->getErrors() );
				$view->setSearchForm( $form );
				$view->setPageId('advanced_search');

				$view->renderView();
			} catch(Exception $e) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}

		/**
		 * fetch the cached data
		 * @todo need to create a more centralized cache fetching method/class
		 *
		 * @param  string $cache_key
		 * @return array
		 * @since  1.0
		 */
		protected function fetchCache($cache_key) {
			$this->load->driver('cache', array('adapter' => 'apc'));

			$cache_array = $this->cache->get($cache_key);
			return $cache_array;
		}
	}

?>
