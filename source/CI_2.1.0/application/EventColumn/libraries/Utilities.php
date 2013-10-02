<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utilities
 *
 * @author stretch
 */
class Utilities {

	public static function getBoolean($value) {
		if (is_bool($value)) {
			return $value;
		}

		$result = false;

		if (strtolower($value) != 'false') {
			$result = (bool) $value;
		}

		return $result;
	}

}

?>
