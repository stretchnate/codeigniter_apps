<?php
	require_once(APPPATH . 'third_party/recaptchalib.php');
	/**
	 * Description of Recaptcha
	 *
	 * @author stretch
	 */
	class Form_Field_Recaptcha extends Form_Field {

		//@todo move these keys to a config file or the db.
		const RECAPTCHA_PUBLIC_KEY = '6LdbwuoSAAAAAPSqvEWCzUjzF4AV239u4d3_LXn1';//eventcolumn.local
		const RECAPTCHA_PRIVATE_KEY = '6LdbwuoSAAAAANM-ZAgTGYs0pJZQbXJKLtE1X7nb';//eventcolumn.local

		public function __construct() {
			parent::__construct();
		}

		/**
		 * Generates the form field
		 *
		 * @return string
		 * @access public
		 * @since 1.0
		 */
		public function generateField() {
			$this->renderField( recaptcha_get_html(self::RECAPTCHA_PUBLIC_KEY) );
		}

		/**
		 * validates the recaptcha field
		 *
		 * @param string $server
		 * @param string $challenge_field
		 * @param string $response_field
		 * @return boolean
		 */
		public static function validate($server, $challenge_field, $response_field) {
			$response = recaptcha_check_answer( self::RECAPTCHA_PRIVATE_KEY, $server, $challenge_field, $response_field );

			return Utilities::getBoolean($response->is_valid);
		}
	}

?>
