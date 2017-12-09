<?php
	abstract class Budget_BaseVW {
		protected $title;
		protected $scripts;
		protected $errors;
		protected $notes;
		protected $CI;

		/**
		 * @var \Budget_DataModel_AccountDM[]
		 */
		private $accounts;

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

			<!DOCTYPE html>
			<html lang="en">
			<head>
				<title><?php echo $this->title ?></title>
				<meta http-equiv="X-UA-Compatible" content="IE=edge" /><!-- support for border-radius in IE9 -->
				<meta charset='utf-8'>
				<!-- ensure proper mobile rendering and touch zooming with the following tag -->
				<meta name='viewport' content='width=device-width, initial-scale=1'>
				<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
				<link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
				<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>main.css" />
				<!--<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>redmond/jquery-ui-1.8.21.custom.css" />-->
				<!--<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>jquery.dataTables_1.9.0.css" />-->
				<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
				<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js'></script>
				<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
				<?php
                if(isset($this->scripts) && is_array($this->scripts)) {
					foreach($this->scripts as $script)
						echo $script."\n\t";
				}
                ?>
				<script type="text/javascript" src="/javascript/nav.js"></script>
			</head>
			<body>
				<nav class="navbar navbar-default">
					<div class="container">
						<div id="header">
							<h1>
								<a href="/"><img id="logo" class="navbar-brand" src="/images/logo.png" alt="<?= COMPANY_NAME; ?>" /></a>
							</h1>
						</div>
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="#" title="Quantum">Q</a>
						</div>
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<?php
								$nav = new NavigationUlLIB("main_nav", "nav navbar-nav");
								echo $nav->getUl();
							?>
							<ul class="nav navbar-nav navbar-right">
								<li class="dropdown">
									<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<?=$this->CI->session->userdata('logged_user')?> <span class="caret"></span>
									</a>
									<ul class="dropdown-menu">
										<li><a href="/userCTL">Profile</a></li>
										<li><a href="/admin/logout">logout</a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</nav>
				<div class="container">
				<?= $this->showAd(AdFactory::AD_AUTO); ?>

				<div id="content">
					<div class="error">
						<?php
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
			</div><!-- end div container -->
			<div class="container-fluid">
            <div id="footer">
                <div class='links'>
                    <?php
                    $footer_nav = new NavigationUlLIB('footer');
                    echo $footer_nav->getUl();
                    ?>
                </div>
                <div id="copy">
                    &copy;2010-<?php $year = date('Y'); echo $year;?> <?=COMPANY_NAME;?>
					<div class="quantum">Powered by <img src="/images/quantum_logo_transparent_bg.png" height="20px" /></div>
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
//            $ad = AdFactory::getAdService();
//            $ad->displayAd($ad_type);
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