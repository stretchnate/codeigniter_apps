<?php //following example on http://www.roscripts.com/PHP_login_script-143.html
    class Register extends N8_Controller {

		function __construct() {
			parent::__construct();
			$this->load->library('auth');	
		}

		function index(){
			$this->auth->restrict();
			$header_data['links'] = $this->utilities->createLinks('main_nav');
			$this->load->view('header', $header_data);
			$this->load->view('registerView');
			$this->load->view('footer');
		}
		
		function registerUser(){
			
		}
	}
?>
