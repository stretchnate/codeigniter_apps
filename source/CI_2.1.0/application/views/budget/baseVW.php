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
				<?php if(isset($this->scripts) && is_array($this->scripts)) {
					foreach($this->scripts as $script)
						echo $script."\n\t"; 
				}?>
				<script type="text/javascript">
					$(document).ready(function() {
						$(".main_nav li, .main_nav li ul").mouseover(function() {
							if( $(this).children("ul").attr("class") != undefined ) {
								$(this).children("ul").show();
							}
						});

						$(".main_nav li, .main_nav li ul").mouseout(function() {
							if( $(this).children("ul").attr("class") != undefined ) {
								$(this).children("ul").hide();
							}
						});
					});
				</script>
			</head>
			<body>
				<div id="header" class="border">
					<div id="console">
						<div id="date">
							Hello <a href="/userCTL"><?=$this->CI->session->userdata('logged_user')?></a><?=" - ".date('l, F j'); ?>
						</div>
					</div>
					<h1><a href="/">Budget 3.1<span style="font-size:40%;">beta</span></a></h1>
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
			?>
				</div><!-- end div content -->
				<div id="post-it-notes">
					<?
					if( isset($this->notes) && is_array($this->notes)) {
						foreach($this->notes as $note) { ?>
						<div class="post-it">
							<?php echo  $note->note_text; ?>
							<br />
							<a href="/notes/showNoteForm/<?php echo $note->note_id;?>">Edit Note</a>
							<br />
							<a href="/notes/deleteNote/<?php echo $note->note_id;?>/<?php echo $uri?>">Delete Note</a>
						</div>
						<?
						}
					} ?>
				</div>
				<div class="clear">&nbsp;</div>
			</div><!-- end div container -->
				<div id="footer" class="border">
					<div id="copy"><a href="/blackjack/blackjack/" target="_blank">Play Blackjack</a>&nbsp;&nbsp;<span class="version">v 3.0</span> &copy; <?php $year = date('Y'); echo $year;?> Me.</div>
				</div>
			</body>
			</html>
		<?
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