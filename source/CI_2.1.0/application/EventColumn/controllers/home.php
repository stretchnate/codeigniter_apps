<?php
	if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

	/**
	 * this is the home page controller
	 *
	 * @author stretch
	 */
	class home extends N8_Controller {

		const VIEW_CONFIG_FILE   = '/var/www/source/CI_2.1.0/application/EventColumn/site_cfg/pageConfig.xml';
		const VIEW_CONFIG_SCHEMA = '/var/www/source/CI_2.1.0/application/EventColumn/site_cfg/pageConfig.xsd';

		protected $home_vw;

		public function __construct() {
			parent::__construct();
			$this->load->view('Home');
			$this->home_vw = new HomeVW();
		}

		public function index() {
			$this->home_vw->setPageId('home');
			$this->home_vw->setMiniSearch(new Plugins_MiniSearch());
			$this->home_vw->renderView();
		}

		/**
		 * retreives the view content from the config file
		 *
		 * @param  string $view
		 * @return string
		 * @since  1.0
		 */
		protected function getViewContent($view) {
			$result     = null;
			$config_xml = new SimpleXMLElement( self::VIEW_CONFIG_FILE, null, true );

			if( Utilities::XMLIsValid( $config_xml, self::VIEW_CONFIG_SCHEMA ) ) {
				$view_config = $config_xml->xpath( 'Page[@id="'.$view.'"]/Body' );
				if(!empty($view_config)) {
					$result = (string)$view_config[0];
				}
			}

			return $result;
		}
	}

?>
