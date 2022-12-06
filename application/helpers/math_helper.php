<?php
	/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *  add two numbers
 *
 * @param float $addend_1
 * @param float $addend_2
 * @param int $scale
 * @param type $mode
 * @return float
 */
function add($addend_1, $addend_2, $scale = 0, $mode = null) {
	// if(function_exists('bcadd')) {
		$sum = bcadd($addend_1, $addend_2, $scale);
	// } else {
	// 	$sum = round(($addend_1 + $addend_2), $scale, $mode);
	// }

	return $sum;
}

/**
 * subtract two numbers
 *
 * @param float $minuend
 * @param float $subtrahend
 * @param int $scale
 * @param int $mode
 * @return float
 */
function subtract($minuend, $subtrahend, $scale = 0, $mode = null) {
	// if(function_exists('bcsub')) {
		$difference = bcsub($minuend, $subtrahend, $scale);
	// } else {
	// 	$difference = round(($minuend - $subtrahend), $scale, $mode);
	// }

	return $difference;
}

/**
 * multiply two numbers
 *
 * @param float $multiplicand
 * @param float $multiplier
 * @param int $scale
 * @param type $mode
 * @return float
 */
function multiply($multiplicand, $multiplier, $scale = 0, $mode = null) {
	// if(function_exists('bcmul')) {
		$product = bcmul($multiplicand, $multiplier, $scale);
	// } else {
	// 	$product = round(($multiplicand * $multiplier), $scale, $mode);
	// }

	return $product;
}

/**
 * divide two numbers
 *
 * @param float $dividend
 * @param float $divisor
 * @param int $scale
 * @param type $mode
 * @return float
 */
function divide($dividend, $divisor, $scale = 0, $mode = null) {
	// if(function_exists('bcdiv')) {
		$quotient = bcdiv($dividend, $divisor, $scale);
	// } else {
	// 	$quotient = round(($dividend / $divisor), $scale, $mode);
	// }

	return $quotient;
}

/**
 * @param $value
 * @return string
 */
function dbNumberFormat($value) {
    return number_format($value, 2, '.', '');
}