<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Jsincludes {

    const JS         = "/javascript/";
    const UTILITIES  = "<script type='text/javascript' src='/javascript/utilities.js'></script>";
    const JQUERY_UI  = "<script type='text/javascript' src='/javascript/jquery-ui-1.8.21.custom.min.js'></script>";
    const DATATABLES = "<script type='text/javascript' src='/javascript/datatables/jquery.dataTables_1.9.0.min.js'></script>";

    function newBook() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."newBook.js'></script>";
        return $scripts;
    }

    function books() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."books.js'></script>";
        return $scripts;
    }

    function newFunds() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."newFunds.js'></script>";
        return $scripts;
    }

    function transferFunds() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."transferFunds.js'></script>";
        return $scripts;
    }

    function editBook() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."editBook.js'></script>";
        return $scripts;
    }

    function home() {
        $scripts[] = "<script type='text/javascript' src='".self::JS."home.js'></script>";
        return $scripts;
    }

    function report() {
        $scripts = array();
        return $scripts;
    }

    function newAccount() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."newAccount.js'></script>";
        return $scripts;
    }

    public static function getUserProfileJS() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."user_profile.js'></script>";
        return $scripts;
    }

    public static function content() {
        $scripts[] = self::UTILITIES;
        return $scripts;
    }
}