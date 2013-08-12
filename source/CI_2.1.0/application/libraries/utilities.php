<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Utilities {

	function Utilities() {
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->model('Utils', 'UTIL', TRUE);
	}

	//deprecated - see nav library
	function createLinks($category, $is_sub_link_array = false) {
		$this->CI->auth->restrict();
		$i = 0;

		$data = $this->CI->UTIL->getLinks($category);

		$links = array();
		if( is_array($data) ) {
			foreach($data as $link) {

				$links[$i]['link']     = $this->buildLink($link);
				$links[$i]['sublinks'] = $this->createLinks($category."|".strtolower($link->link_name), true);

				$i++;
			}
		}

		return $links;
	}

	//deprecated - see nav library
	public function buildLink($link_data) {
		$attributes = array();

		foreach($link_data as $index => $value) {
			switch($index) {
				case "link_url":
				case "link_name":
					break;
				case "title":
				case "class":
				case "id":
					if( !empty($value) ) {
						$attributes[] = $index.'="'.$value.'"';
					}
					break;
				case "type":
				case "rel":
				case "media":
				case "href":
					if( !empty($value) ) {
						$link_tag[$index] = $value;
					}
					break;
			}
		}
		if( isset($link_tag) && count($link_tag) > 0 ) {
			$link = link_tag($link_tag);
		} else {
			$link = anchor($link_data->link_url, $link_data->link_name, $attributes);
		}

		return $link;
	}

	public static function isWindows() {
		$result = false;
		if(strpos(PHP_OS, "WIN") !== false) {
			$result = true;
		}

		return $result;
	}

	/**
	 * converts a variable to a boolean value 'false' will return (bool)false
	 * 
	 * @param mixed $var
	 * @return bool
	 */
	public static function getBoolean($var) {
		if(is_bool($var)) {
			return $var;
		}

		$result = false;
		if($var != 'false') {
			$result = (bool)$var;
		}

		return $result;
	}
}