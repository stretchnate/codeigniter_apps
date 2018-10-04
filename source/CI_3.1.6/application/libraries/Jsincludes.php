<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Jsincludes {

    const JS         = "/javascript/";
    const UTILITIES  = "<script type='text/javascript' src='/javascript/utilities.js'></script>";
    const JQUERY_UI  = "<script type='text/javascript' src='/javascript/jquery-ui-1.8.21.custom.min.js'></script>";

    public function newBook() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."newBook.js'></script>";
        return $scripts;
    }

    public function books() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."books.js'></script>";
        return $scripts;
    }

    public function newFunds() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."newFunds.js'></script>";
        return $scripts;
    }

    public function transferFunds() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."transferFunds.js'></script>";
        return $scripts;
    }

    public function editBook() {
        $scripts[] = self::UTILITIES;
        $scripts[] = "<script type='text/javascript' src='".self::JS."editBook.js'></script>";
        return $scripts;
    }

    public function home() {
        $scripts[] = "<script type='text/javascript' src='".self::JS."home.js'></script>";
        $scripts[] = "<script type='text/javascript' src='".self::JS."plaid.js'></script>";
        return $scripts;
    }

    public function reports() {
        $scripts[] = '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
        $scripts[] = "<script type='text/javascript' src='".self::JS."report.js'></script>";
        return $scripts;
    }

    public function reportForm() {
        $scripts[] = "<script type='text/javascript' src='".self::JS."report_form.js'></script>";
        return $scripts;
    }

    public function newAccount() {
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