<?php
    require_once('TemplateInterface.php');

    /**
     * Description of Base
     *
     * @author stretch
     */
    abstract class Base implements TemplateInterface {

        abstract protected function generateView();

        public function __construct() {}

        public function renderView() {
            $this->startBody();
            $this->generateMainNav();
            $this->generateView();
            $this->generateFooter();
            $this->endPage();
        }

        protected function startBody() {
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
					<title>Clermont Elders Quorum Home Teaching Reporting</title>
                    <link rel='stylesheet' href='/css/base.css' type='text/css' />
                    <script src="/js/jquery.1.10.2.min.js"></script>
                    <script src="/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
                    <script src="/js/jquery/validation/dist/jquery.validate.1.11.1.min.js"></script>
                    <script src="/js/ceq.js"></script>
				</head>
				<body>
					<div id="wrapper">
        <?
        }

        protected function generateMainNav() {

        }

        protected function generateFooter() {

        }

        protected function endPage() {
            ?>
					</div><!-- end div id wrapper -->
				</body>
			</html>
			<?php
        }
    }
