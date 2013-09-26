<?php

abstract class BaseVW {

	protected $title;
	protected $scripts;
	protected $errors;

	abstract public function generateView();

	public function __construct() {

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
				<link rel="stylesheet" href="/css/base.css" type="text/css" />
				<!-- need to make the js below dynamically load only on the maps page(s) -->
				<script src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
				<script>
					function initialize() {
						var map_canvas = document.getElementById('map_canvas');
						var map_options = {
							center: new google.maps.LatLng(44.5403, -78.5463),
							zoom: 8,
							mapTypeId: google.maps.MapTypeId.ROADMAP
						};

						var map = new google.maps.Map(map_canvas, map_options);

					}

					google.maps.event.addDomListener(window, 'load', initialize);
				</script>
				<!-- end dynamically loaded js -->


			</head>
			<body>
				<div id="wrapper">
					<div id="header">
						<h1><img src="/images/header_tx.png" alt="Event Column, create and find events" /><span>Welcome <?= "need a variable here"; ?></span></h1>
					</div>
					<div id="main-nav">
						<ul>
							<li>Map</li>
							<li class="selected">Create</li>
							<li style="margin-right:0px;">Calendar</li>
							<li class="last">&nbsp;</li>
						</ul>
						<div class="clear">&nbsp;</div>
					</div>
					<div id="content">
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
							<li>About Us | </li>
							<li>Contact Us | </li>
							<li>Policies | </li>
							<li>Add these to the db</li>
						</ul>
					</div>
				</div><!-- end div id wrapper -->
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

}
?>