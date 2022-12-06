<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	This file extends the CI date_helper file in ./system/helpers/
 */
 
/**
 * This method is for finding the due date of a given account, just pass the due day and (optional) format
 */
if ( ! function_exists('dueDate')) {
	function dueDate($date, $format = "M d, Y") {
		if( $date >= (int)date('d') ) {
			$due_date = date( $format, mktime(0,0,0,date('m'),$date,date('Y')));
		} else {
			$due_date = date( $format, mktime(0,0,0,date('m')+1,$date,date('Y')));
		}
		return $due_date;
	}
}

/* End of file N8_date_helper.php */
/* Location: ./application/helpers/N8_date_helper.php */