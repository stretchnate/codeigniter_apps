<?php
	/**
	 * this class is used to mask/unmask the event_id so we
	 * don't show the actual event id in the url
	 *
	 * @author stretch
	 */
	class EventMask {

		const THE_MEANING_OF_LIFE_THE_UNIVERSE_AND_EVERYTHING = 42;

		/**
		 * event_mask_array is the array used to mask event id's.
		 * the id is masked by multiplying the actual event id
		 * by self::THE_MEANING_OF_LIFE_THE_UNIVERSE_AND_EVERYTHING
		 * then masking the new value with alpha characters from the list below
		 * each number (0-9) has 4 alpha characters associated with it.
		 * a random character is chosen to represent the actual number value
		 * for id's that are still less than 10 chars long after the multiplication
		 * we add a '.' to the left of the actual id mask and then pad the id up to 10 chars
		 * all numbers and the '.' are masked with alpha chars.
		 * here is an example:
		 *	   PAtYcfbyKd = 436059.420
		 *     strip all chars to the left of and including the '.'
		 *     420 / self::THE_MEANING_OF_LIFE_THE_UNIVERSE_AND_EVERYTHING;
		 *     10 is the actual event id.
		 *
		 */
		public static $event_mask_array = array(
			"0" => array("i", "q", "F", "d", "Y"),
			"1" => array("E", "k", "Z", "n", "W"),
			"2" => array("u", "S", "j", "K", "u"),
			"3" => array("A", "m", "G", "z", "r"),
			"4" => array("o", "y", "C", "J", "P"),
			"5" => array("U", "V", "g", "N", "c"),
			"6" => array("a", "L", "t", "X", "a"),
			"7" => array("I", "s", "D", "l", "p"),
			"8" => array("e", "H", "w", "T", "R"),
			"9" => array("O", "x", "B", "f", "O"),
			"." => array("b", "Q", "v", "M", "h")
		);

		/**
		* masks the event id using self::$event_mask_array
		* see self::$event_mask_array for  more details
		*
		* @param int $event_id
		* @return string
		* @since 1.0
		*/
	   public static function maskEventId($event_id) {
		   $no_dot = true;
		   $multiple = (string)($event_id * self::THE_MEANING_OF_LIFE_THE_UNIVERSE_AND_EVERYTHING);
		   $mask_length = strlen($multiple);
		   $mask = '';
		   $i = 0;

		   while($i < $mask_length) {
			   $char = substr($multiple, $i, 1);
			   $char_rand = rand(0, 4);
			   $mask .= self::$event_mask_array[$char][$char_rand];
			   $i++;
		   }

		   //make sure we have at least 10 characters
		   while(strlen($mask) < 10) {
			   if($no_dot === true) {
				   //the dot tells us where the real id begins
				   $dot = rand(0,4);
				   $mask = self::$event_mask_array["."][$dot].$mask;
				   $no_dot = false;
			   }

			   $_1_level = rand(0, 9);
			   $_2_level = rand(0,4);
			   $mask = self::$event_mask_array[$_1_level][$_2_level] . $mask;
		   }

		   return $mask;
	   }

	   /**
		* unmasks the event_id based on self::$event_mask_array
		* see self::$event_mask_array for more details
		*
		* @param string $event_id
		* @return int
		* @since 1.0
		*/
	   public static function unmaskEventId($event_id) {
		   $i = 0;
		   $unmasked = '';
		   $event_id_length = strlen($event_id);

		   while($i < $event_id_length) {
			   $char = substr($event_id, $i, 1);
			   $i++;

			   foreach(self::$event_mask_array as $index => $char_array) {
				   if(in_array($char, $char_array)) {
					   $unmasked .= $index;
					   continue 2;
				   }
			   }
		   }

		   if(strpos($unmasked, ".") !== false) {
			   $result = explode(".", $unmasked);
			   $result = $result[1];
		   } else {
			   $result = $unmasked;
		   }

		   return $result / self::THE_MEANING_OF_LIFE_THE_UNIVERSE_AND_EVERYTHING;
	   }
	}

?>
