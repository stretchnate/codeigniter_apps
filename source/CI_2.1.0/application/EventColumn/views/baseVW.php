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
		private $mini_search;

		protected $errors;
		protected $messages;
		protected $show_main_nav	 = true;
		protected $username		 = 'Guest';
		protected $page_id		 = 'default';

		abstract protected function generateView();

		public function __construct() {
			if( isset( $this->session ) ) {
				$username = $this->session->userdata( 'username' );
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
		protected function generateHeader() {
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
						<div id="header">
							<h1><img src="/images/header_tx.png" alt="Event Column, create and find events" /></h1>
							<span class="welcome">
								Welcome <span class="purple"><?=$this->username; ?></span> |
								<?php
								if( $this->username !== 'Guest' ) {
									?>
									<a href="/login/logout">Logout</a>
									<?php
								} else {
									?>
									<a href="/login">Login</a>
									<?php
								}
								?>
							</span>
						</div>
						<?php
						if( $this->show_main_nav !== false ) {
							?>

							<div id="main-nav">
								<ul>
									<li><a href="/map">Map</a></li>
									<li class="selected"><a href="/event">Create</a></li>
									<li style="margin-right:0px;">Calendar</li>
									<li class="last">&nbsp;</li>
								</ul>
								<div class="clear">&nbsp;</div>
							</div>

							<?php
						}
						echo isset($this->mini_search) ? $this->mini_search->renderForm() : '';
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
					</div><!-- end div id wrapper -->
				</body>
			</html>
			<?php
		}

		private function buildHead() {
			$config_xml = new SimpleXMLElement( self::VIEW_CONFIG_FILE, null, true );

			if( Utilities::XMLIsValid( $config_xml, self::VIEW_CONFIG_SCHEMA ) ) {
				$default_config = $config_xml->xpath( 'Page[@id="default"]/Head' );
				if( ! empty( $default_config ) ) {
					$default_config = $default_config[0];

					if( $default_config instanceof SimpleXMLElement ) {
						foreach( $default_config->children() as $head_item ) {
							$this->addHeadItem( $head_item );
						}
					}
				}

				$page_config = $config_xml->xpath( 'Page[@id="' . $this->page_id . '"]/Head' );

				if( ! empty( $page_config ) ) {
					$page_config = $page_config[0];

					if( $page_config instanceof SimpleXMLElement ) {
						foreach( $page_config->children() as $head_item ) {
							$this->addHeadItem( $head_item );
						}
					}
				}
			}
		}

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
		public function renderView() {
			$this->generateHeader();
			$this->generateView();
			$this->generateFooter();
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

		public function showMainNav( $show_main_nav ) {
			$this->show_main_nav = Utilities::getBoolean( $show_main_nav );
		}

		public function setPageId( $page_id ) {
			$this->page_id = $page_id;
		}

		public function setMiniSearch(Plugins_MiniSearch $mini_search) {
			$this->mini_search = $mini_search;
		}
	}
?>