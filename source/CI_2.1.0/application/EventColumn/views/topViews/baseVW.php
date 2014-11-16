<?php

	abstract class BaseVW {

		const VIEW_CONFIG_FILE	 = '/var/www/source/CI_2.1.0/application/EventColumn/site_cfg/pageConfig.xml';
		const VIEW_CONFIG_SCHEMA	 = '/var/www/source/CI_2.1.0/application/EventColumn/site_cfg/pageConfig.xsd';

		private $css_array	 = array( );
		private $js_array	 = array( );
		private $inline_js_array = array();
		private $meta_array	 = array( );
		private $title;
		private $title_arg;

		protected $errors;
		protected $messages;
		protected $username		 = 'Guest';
		protected $page_id		 = 'default';
		protected $categories_nav;

		abstract protected function generateView();
		abstract protected function generateHeader();

		/**
		 * baseVW construct method.
		 */
		public function __construct() {
			$ci =& get_instance();
			if( isset( $ci->session ) ) {
				$username = $ci->session->userdata( 'username' );
				if( ! empty( $username ) ) {
					$this->username = $username;
				}
			}
		}

		/**
		 * generates the view header
		 *
		 * @return false;
		 * @access protected
		 * @since 1.0
		 */
		protected final function startBody() {
			$this->buildHead();
			//Set no caching
			header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
			header( "Cache-Control: no-store, no-cache, must-revalidate" );
			header( "Cache-Control: post-check=0, pre-check=0", false );
			header( "Pragma: no-cache" );
			?>
			<!DOCTYPE html>
			<html>
				<head>
					<title><?=sprintf($this->title, $this->title_arg); ?></title>
					<?
					foreach( $this->css_array as $css ) {
						echo '<link rel="stylesheet" href="' . $css . '" type="text/css" />';
					}

					foreach( $this->js_array as $js ) {
						echo '<script src="' . $js . '"></script>';
					}

					foreach($this->inline_js_array as $inline_js) {
						echo '<script type="text/javascript">' . $inline_js . '</script>';
					}

					foreach( $this->meta_array as $meta_name => $meta_content ) {
						echo '<meta name="' . $meta_name . '" content="' . $meta_content . '" />';
					}
					?>

				</head>
				<body>
					<div id="wrapper">
			<?php
		}

		/**
		 * generates the main nav
		 *
		 * @return void
		 * @access protected
		 * @since  1.1
		 */
		protected final function generateMainNav() {
			?>
						<div id="main-nav">
							<ul>
								<li><a href="/map">Map</a></li>
								<li class="selected"><a href="/event/create">Create</a></li>
								<!--li style="margin-right:0px;">Calendar</li-->
							</ul>
							<div class="clear">&nbsp;</div>
						</div>
			<?php
		}

		/**
		 * begines the content <div>
		 *
		 * @return void
		 * @access protected
		 * @since 1.1
		 */
		protected function beginContent() {
			?>
						<div id="content">
							<div id="error_messages" class="error">
								<?php
								if( is_array( $this->messages ) ) {
									foreach( $this->messages as $message_type => $message_array ) {
										foreach( $message_array as $message ) {
											echo '<div class="' . $message_type . '">' . $message . '</div>';
										}
									}
								}
								?>
							</div>
			<?php
		}

		/**
		 * generates the view footer
		 *
		 * @return void
		 * @access protected
		 * @since 1.0
		 */
		protected function generateFooter() {
							?>
						</div><!-- end div id content-->
						<div id="footer">
							<ul>
								<li><a href="/content/about">About Us</a> | </li>
								<li><a href="/content/contactUs">Contact Us</a> | </li>
								<li><a href="/content/policies">Policies</a></li>
							</ul>
						</div>
			<?php
		}

		/**
		 * ends the html of the page
		 *
		 * @return void
		 * @access private
		 * @since 1.1
		 */
		private function endPage() {
			?>
					</div><!-- end div id wrapper -->
				</body>
			</html>
			<?php
		}

		/**
		 * builds the <head> elements
		 *
		 * @return void
		 * @access private
		 * @since 1.0
		 */
		private function buildHead() {
			$config_xml = new SimpleXMLElement( self::VIEW_CONFIG_FILE, null, true );
			if( Utilities::XMLIsValid( $config_xml, self::VIEW_CONFIG_SCHEMA ) ) {
				$default_config = $config_xml->xpath( 'Page[@id="default"]/Head' );
				if( ! empty( $default_config ) && $default_config[0] instanceof SimpleXMLElement) {
					$this->setHeadItems($default_config[0]);
				}

				$page_config = $config_xml->xpath( 'Page[@id="' . $this->page_id . '"]/Head' );
				if( ! empty( $page_config ) && $page_config[0] instanceof SimpleXMLElement) {
					$this->setHeadItems($page_config[0]);
				}
			}
		}

		/**
		 * sets head items for the <head> element
		 *
		 * @param SimpleXMLElement $config
		 * @return void
		 * @access private
		 * @since 1.1
		 */
		private function setHeadItems(SimpleXMLElement $config) {
			foreach( $config->children() as $head_item ) {
				$this->addHeadItem( $head_item );
			}
		}

		/**
		 * adds a head item for the <head> element
		 *
		 * @param SimpleXMLElement $head_item
		 * @return void
		 * @access private
		 * @since 1.1
		 */
		private function addHeadItem( SimpleXMLElement $head_item ) {
			switch( $head_item->getName() ) {
				case 'title':
					if(is_null($this->title)) {
						$this->title									 = (string) $head_item;
					}
					break;
				case 'css':
					if(!in_array((string)$head_item, $this->css_array)) {
						$this->css_array[]								 = (string) $head_item;
					}
					break;
				case 'js':
					if(!in_array((string)$head_item, $this->js_array)) {
						$this->js_array[]								 = sprintf((string) $head_item);
					}
					break;
				case 'inline_js':
					if(!in_array((string)$head_item, $this->inline_js_array)) {
						$this->inline_js_array[]                         = (string) $head_item;
					}
					break;
				case 'meta':
					if(!in_array((string)$head_item, $this->meta_array)) {
						$this->meta_array[(string) $head_item['name']]	 = (string) $head_item;
					}
					break;
			}
		}

		/**
		 * renders the entire view from header to footer
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0
		 */
		public final function renderView() {
			$this->startBody();
			$this->generateHeader();
			$this->generateMainNav();
			$this->beginContent();

			if($this->categories_nav) {
				$this->renderCategoriesNav();
			}

			$this->generateView();
			$this->generateFooter();
			$this->endPage();
		}

		/**
		 * builds a categories nav
		 *
		 * @return void
		 * @access protected
		 * @since 1.0
		 */
		protected function renderCategoriesNav() {
			echo "<div id='side-nav'>".$this->categories_nav->getUL()."</div>";
		}

		public function setTitle( $title ) {
			$this->title = $title;
		}

		public function setTitleArg($title_arg) {
			$this->title_arg = $title_arg;
		}

		public function setErrorMessages( $messages ) {
			$this->setErrors( $messages );
		}

		public function setErrors( $errors ) {
			$this->messages['error'] = is_array( $errors ) ? $errors : array( $errors );
		}

		public function setSuccessMessages( $messages ) {
			$this->messages['success'] = is_array( $messages ) ? $messages : array( $messages );
		}

		public function setNotificationMessages( $messages ) {
			$this->messages['notification'] = is_array( $messages ) ? $messages : array( $messages );
		}

		public function setPageId( $page_id ) {
			$this->page_id = $page_id;
		}

		public function setMiniSearch(Plugins_MiniSearch $mini_search) {
			$this->mini_search = $mini_search;
		}

		public function setCategoriesNav(DataList $categories_nav) {
			$this->categories_nav = $categories_nav;
			return $this;
		}
	}
?>