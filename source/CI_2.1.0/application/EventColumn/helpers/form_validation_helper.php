<?php

/*
 * this file provides a central place to put form_validation callback methods.
 * you will still have to create the callback in the class that is doing the validation
 * but the callback can use these methods to do the actual validation thus sticking closer to the
 * write once run anywhere rule.
 */

function alpha_special($str) {
	return preg_match("/^([\w!\$@%\*&\^\?\|\+=\.-]{8,32})$/i", $str);
}

?>
