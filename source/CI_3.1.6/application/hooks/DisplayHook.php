<?php // application/hooks/DisplayHook.php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/19/18
 * Time: 9:39 PM
 */

class DisplayHook {
    public function captureOutput() {
        if (ENVIRONMENT != 'testing') {
			$this->CI =& get_instance();
			if(isset($this->CI->output)) {
				echo $this->CI->output->get_output();
			}
        }
    }
}