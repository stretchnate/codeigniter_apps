<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Jsincludes {

	const JS         = "/javascript/";
    const UTILITIES  = "<script type='text/javascript' src='/javascript/utilities.js'></script>";
//	const JQUERY     = "<script type='text/javascript' src='/javascript/jquery-1.7.2.min.js'></script>";
//	const JQUERY     = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>";
	const JQUERY_UI  = "<script type='text/javascript' src='/javascript/jquery-ui-1.8.21.custom.min.js'></script>";
	const DATEPICKER = "<script type='text/javascript' src='/javascript/datepicker.js'></script>";
	const DATATABLES = "<script type='text/javascript' src='/javascript/datatables/jquery.dataTables_1.9.0.min.js'></script>";
//	const BOOTSTRAP  = "<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>";

	function __construct() {

	}

	function newBook() {
//		$scripts[] = self::JQUERY_UI;
        $scripts[] = self::UTILITIES;
		$scripts[] = "<script type='text/javascript' src='".self::JS."newBook.js'></script>";
		return $scripts;
	}

	function books() {
//		$scripts[] = self::JQUERY_UI;
//		$scripts[] = self::DATEPICKER;
//		$scripts[] = self::DATATABLES;
		$scripts[] = self::UTILITIES;
		$scripts[] = "<script type='text/javascript' src='".self::JS."books.js'></script>";
		return $scripts;
	}

	function newFunds() {
//		$scripts[] = self::JQUERY_UI;
//		$scripts[] = self::DATEPICKER;
		$scripts[] = self::UTILITIES;
		$scripts[] = "<script type='text/javascript' src='".self::JS."newFunds.js'></script>";
		return $scripts;
	}

	function transferFunds() {
//		$scripts[] = self::JQUERY_UI;
//		$scripts[] = self::DATEPICKER;
//		$scripts[] = self::DATATABLES;
		$scripts[] = self::UTILITIES;
		$scripts[] = "<script type='text/javascript' src='".self::JS."transferFunds.js'></script>";
		return $scripts;
	}

	function editBook() {
//		$scripts[] = self::JQUERY_UI;
		$scripts[] = self::UTILITIES;
		$scripts[] = "<script type='text/javascript' src='".self::JS."editBook.js'></script>";
		return $scripts;
	}

	function home() {
//		$scripts[] = self::JQUERY_UI;
		$scripts[] = "<script type='text/javascript' src='".self::JS."home.js'></script>";
		return $scripts;
	}

	function report() {
		$scripts = array();
//		$scripts[] = self::JQUERY_UI;
//		$scripts[] = self::DATEPICKER;
//		$scripts[] = self::DATATABLES;
		return $scripts;
	}

	function newAccount() {
//		$scripts[] = self::JQUERY_UI;
		$scripts[] = self::UTILITIES;
		$scripts[] = "<script type='text/javascript' src='".self::JS."newAccount.js'></script>";
		return $scripts;
	}

	public static function getUserProfileJS() {
//		$scripts[] = self::JQUERY_UI;
		$scripts[] = "<script type='text/javascript' src='".self::JS."user_profile.js'></script>";
		return $scripts;
	}

    public static function content() {
//		$scripts[] = self::JQUERY_UI;
		$scripts[] = self::UTILITIES;
        return $scripts;
    }
}