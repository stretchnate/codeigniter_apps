<?php
class PhpInfo extends N8_Controller {
	public function __construct() {}

	public function index() {
		phpinfo();
	}
}

