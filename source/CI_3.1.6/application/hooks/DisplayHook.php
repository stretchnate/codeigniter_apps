<?php // application/hooks/DisplayHook.php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/19/18
 * Time: 9:39 PM
 */

class DisplayHook {
	public function captureOutput() {
		$this->CI =& get_instance();
		$output = $this->CI->output->get_output();

		if (ENVIRONMENT != 'testing') {
			echo $output;
		}
	}
}