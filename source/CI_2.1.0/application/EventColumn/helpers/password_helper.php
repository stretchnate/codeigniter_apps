<?php
	/**
	 * this class helps with generating random passwords. The methods in this class are static
	 *
	 * @author stretch
	 */
	class passwordHelper {

		const ALPHA = 'A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z';
		const NUMERIC = '0,1,2,3,4,5,6,7,8,9';
		const SPECIAL_CHARS = '!,$,@,%,*,&,^,?,|,+,=,.,-';

		/**
		 * generates a random password
		 *
		 * @return string
		 * @since 1.0
		 * @access public
		 */
		public static function generatePassword() {
			$password_length = self::getPasswordLength();
			$chars			 = self::getPasswordCharsArray();
			$char_count		 = count( $chars );
			$password = '';

			while(strlen($password) < $password_length) {
				$index = mt_rand(0, $char_count);
				$password .= $chars[$index];
			}

			return $password;
		}

		/**
		 * selects a random password length
		 *
		 * @return int
		 * @since 1.0
		 * @access private
		 */
		private static function getPasswordLength() {
			return mt_rand(8, 32);
		}

		/**
		 * builds the password character array
		 *
		 * @return type
		 * @since 1.0
		 * @access private
		 */
		private static function getPasswordCharsArray() {
			$chars = strtolower(self::ALPHA);
			$chars .= ',' . self::ALPHA;
			$chars .= ',' . self::NUMERIC;
			$chars .= ',' . self::SPECIAL_CHARS;

			return explode(',', $chars);
		}
	}

?>
