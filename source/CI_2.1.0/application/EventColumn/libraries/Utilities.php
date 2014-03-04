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

	/**
	 * validates an SimpleXMLElement Object against a schema
	 *
	 * @param SimpleXMLElement $simple_xml_element
	 * @param string $schema_path
	 * @return boolean
	 */
	public static function XMLIsValid(  SimpleXMLElement $simple_xml_element, $schema_path) {
		$result = false;
		$xml_dom_node = dom_import_simplexml($simple_xml_element);

		if($xml_dom_node !== false) {
			$xml_dom = $xml_dom_node->ownerDocument;

			if( file_exists( $schema_path )) {
				$result = $xml_dom->schemaValidate($schema_path);
			}
		}

		return $result;
	}

	/**
	 * formats a date
	 *
	 * @param mixed $date
	 * @param string $format
	 * @return string
	 */
	public function formatDate($date, $format = 'Y-m-d H:i:s') {
		if(!is_numeric($date)) {
			$date = strtotime($date);
		}

		return date($format, $date);
	}

	/**
	 * determines if the current invocation is running via CLI
	 *
	 * @return bool
	 * @static
	 */
	public static function isCLI() {
		return php_sapi_name() === 'cli';
	}
}

?>
