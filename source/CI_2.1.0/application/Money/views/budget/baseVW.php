<?php
	abstract class Budget_BaseVW {
		protected $title;
		protected $scripts;
		protected $errors;
		protected $notes;
		protected $CI;


		abstract protected function generateView();

		public function __construct(&$CI) {
			$this->CI = $CI;
		}

		/**
		 * generates the view header
		 */
		protected function generateHeader() {
			//Set no caching
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			?>

			<!DOCTYPE html PUBLIC  "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
			<head>
				<title><?php echo $this->title ?></title>
				<meta http-equiv="X-UA-Compatible" content="IE=edge" /><!-- support for border-radius in IE9 -->
				<link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
				<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>base.css" />
				<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>redmond/jquery-ui-1.8.21.custom.css" />
				<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>jquery.dataTables_1.9.0.css" />
				<?php
                if(isset($this->scripts) && is_array($this->scripts)) {
					foreach($this->scripts as $script)
						echo $script."\n\t";
				}
                ?>
				<script type="text/javascript" src="/javascript/nav.js"></script>
			</head>
			<body>
				<div id="header" class="border">
                    <div id="date">
                        <?=" ".date('n.j.y'); ?>&nbsp;&nbsp;
                        <?
                        if($this->CI->session->userdata('logged_user')) { ?>
                        <a href='/admin/logout/'>logout</a>
                        <?
                        } ?>
                    </div>
					<div id="console">
						<div id="user">
							Welcome
                            <a href="/userCTL"><?=$this->CI->session->userdata('logged_user')?></a>
						</div>
					</div>
					<h1><a href="/">Smart Budget<span style="font-size:40%;"></span></a></h1>
					<div id="nav">
                        <div class="nav-background">
							<?php
								$nav = new NavigationUlLIB("main_nav");
								echo $nav->getUl();
							?>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<div id="container">
                    <?= $this->showAd(AdFactory::AD_AUTO); ?>
					<?php
					if( isset($sidebar_links) ) { ?>
					<div id="sidebar">
					<?php
						echo $sidebar_links;
					?>
					</div>
					<?php
					} ?>
					<div id="content">
						<div class="error">
							<?
							if(isset($this->errors) && is_array($this->errors)) {
								foreach($this->errors as $error) {
									echo urldecode($error)."<br />";
								}
							}
							?>
						</div>
		<?php
		}

		/**
		 * generates the view footer
		 */
		protected function generateFooter() {
			$uri = str_replace("/", "_", $this->CI->uri->uri_string());
            $n = 0;
			?>
				</div><!-- end div content -->
				<div id="post-it-notes">
					<?php
					if( isset($this->notes) && is_array($this->notes)) {
						foreach($this->notes as $note) {
                            $n++;

                            if($n == 2) {
                                $this->showAd(AdFactory::AD_WIDE_SKYSCRAPER);
                            }
                    ?>
						<div class="post-it">
							<?php echo  $note->note_text; ?>
							<br />
							<a href="/notes/showNoteForm/<?php echo $note->note_id;?>">Edit Note</a>
							<br />
							<a href="/notes/deleteNote/<?php echo $note->note_id;?>/<?php echo $uri?>">Delete Note</a>
						</div>
						<?php
						}
					}

                    if($n < 2) {
                        $this->showAd(AdFactory::AD_WIDE_SKYSCRAPER);
                    }
                    ?>
				</div>
				<div class="clear">&nbsp;</div>

            <div id="footer">
                <div class='links'>
                    <?php
                    $footer_nav = new NavigationUlLIB('footer');
                    echo $footer_nav->getUl();
                    ?>
                </div>
                <div id="copy">
                    &copy;2010-<?php $year = date('Y'); echo $year;?> stretchnate.com
                    <!--<span class="version">v3.2</span>-->
                    <!--<a href="/blackjack/blackjack/" target="_blank">Play Blackjack</a>-->
                </div>
            </div>
            <?= $this->showAd(AdFactory::AD_AUTO); ?>
            </div><!-- end div container -->
			</body>
			</html>
		<?php
		}

        /**
         * displays an adservice ad
         *
         * @return void
         */
        protected function showAd($ad_type) {
            $ad = AdFactory::getAdService();
            $ad->displayAd($ad_type);
        }

		/**
		 * renders the entire view from header to footer
		 *
		 * @return  void
		 * @access  public
		 * @since   07.01.2013
		 */
		public function renderView() {
			$this->generateHeader();
			$this->generateView();
			$this->generateFooter();
		}

		public function setTitle($title) {
			$this->title = $title;
		}

		public function setScripts($scripts) {
			$this->scripts = $scripts;
		}

		public function setErrors($errors) {
			$this->errors = $errors;
		}

		public function setNotes($notes) {
			$this->notes = $notes;
		}

		public function setCI($ci) {
			$this->CI = $ci;
		}
	}

?>