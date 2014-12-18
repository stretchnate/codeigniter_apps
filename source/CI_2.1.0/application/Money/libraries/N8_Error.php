<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class N8_Error {

	private $messages = array(
							"error" => array(),
							"debug" => array(),
							"info"  => array()
							);

	function __construct() {
		// log_message('debug', "N8_Error Class Initialized");
	}

	/**
	 * @return array $this->errors
	 */
	public function getErrors() {
		return $this->getMessages("error");
	}

	/**
	 * Sets the message into the array which is publicly accessible via getErrors()
	 * logs message and message_type
	 *
	 * @param String $message
	 * @param string $message_type  ex. 'error', 'debug', 'info'
	 * @return void
	 */
	protected function setError($message, $message_type = "error") {
		log_message($message_type, $message);
		$this->messages[$message_type][] = $message;
	}

	/**
	 * Quick check to see if we have any errors
	 *
	 * @return bool
	 */
	public function isErrors() {
		$return = false;
		if( count($this->messages["error"]) > 0 ) {
			$return = true;
		}
		return $return;
	}

	public function getMessages($message_type) {
		return $this->messages[$message_type];
	}
}