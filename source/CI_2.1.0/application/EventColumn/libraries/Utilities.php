<?php

/**
 * a collection of utility functions that can be called statically
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

	/**
	 * shows the 500 error page, takes an exception as an argument and puts the data from the
	 * exception in a hidden field on the error page.
	 *
	 * @param string $message
	 * @param Exception $exception
	 */
	public static function show500($message, Exception $exception = null) {
		if( $exception ) {
			$file = null;
			if(method_exists( $exception, 'getFile')) {
				$i = strrpos($exception->getFile(), '/') + 1;
				$file = trim(substr($exception->getFile(), $i), '.php');
			}

			$line = null;
			if(method_exists( $exception, 'getLine' )) {
				$line = $exception->getLine();
			}

			$ex_message = null;
			if(method_exists( $exception, 'getMessage' )) {
				$ex_message = $exception->getMessage();
			}

			$message .= "<br /><span style='display:none'>Message: ". $ex_message . ' (' . $file . ':' . $line . ')</span>';
		}

		N8_Error::logMessage( '500 Error: '.$message, N8_Error::ERROR );
		show_error( $message, 500, 'Bummer!' );
	}
}

?>
