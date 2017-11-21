<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	This file extends the CI date_helper file in ./system/helpers/
 */

/**
 * This method is for finding the due date of a given account, just pass the due day and (optional) format
 */
if ( ! function_exists('void_link')) {
	function void_link($text, $attributes = array()) {
		$link = "<a href='javascript:void(null)'";

		if(is_array($attributes)) {
			foreach($attributes as $attribute => $value) {
				$link .= " ".$value;
			}
		}

		$link .= ">".$text."</a>";
		return $link;
	}
}

if(!function_exists('dropdown_link')) {
	function dropdown_link($text) {
		return "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>$text <span class='caret'></span></a>";
	}
}

/* End of file N8_date_helper.php */
/* Location: ./application/helpers/N8_date_helper.php */