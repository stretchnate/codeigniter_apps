<?php

abstract class BaseVW {

	protected $title;
	protected $scripts;
	protected $errors;
	protected $CI;

	abstract public function generateView();

	public function __construct(&$CI) {
		$this->CI = $CI;
	}

	/**
	 * generates the view header
	 *
	 * @return false;
	 * @access protected
	 * @since 1.0
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
		<html>
			<head>
				<title>Woot!</title>
			</head>
			<body>
				<h1>This is event view</h1>
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
			</body>
		</html>
		<?php
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

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setScripts($scripts) {
		$this->scripts = $scripts;
	}

	public function setErrors($errors) {
		$this->errors = $errors;
	}

	public function setCI($ci) {
		$this->CI = $ci;
	}

}
?>