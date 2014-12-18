<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Generate Navigation Unordered Lists (ul) from the links table in the db.
 * Also creates sub-ul within an li.
 *
 * @author dnate
 * @since 2012.06.18
 * @param links_array
 * @param sublinks_array
 * @param ul String
 * @param category
 */
class NavigationUlLIB {

	protected $links_array    = array();
	protected $sublinks_array = array();
	protected $ul;
	protected $category;

	/**
	 * @param $category String Optional
	 * @return void
	 */
	function __construct( $category = null ) {
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->model('Utils', 'UTIL', TRUE);

		if( $category ) {
			$this->setCategory($category);
			$this->setUl($category);
			$this->buildNav();
		}
	}

	/**
	 * Main driving method
	 *
	 * @return void
	 */
	public function buildNav() {
		$this->createLinksArray();
		$this->generateUl();
	}

	/**
	 * retrieve the links data from mysql.links
	 *
	 * @return Object
	 */
	public function retrieveLinks() {
		return $this->CI->UTIL->getLinks($this->category);
	}

	/**
	 * creates an array of links from the links db table and creates the a href tag
	 *
	 * @return void
	 */
	private function createLinksArray() {
		$this->CI->auth->restrict();

		$data = $this->retrieveLinks();

		if( is_array($data) && count($data) > 0 ) {
			foreach($data as $link) {

				$index = strtolower(str_replace(" ", "_", $link->link_name));

				$this->links_array[$index]    = $this->buildLink($link);

				if($this->CI->UTIL->getLinks($this->category."|".strtolower($link->link_name))) {
					$nav = new NavigationUlLIB();
					$nav->setCategory($this->category."|".strtolower($link->link_name));
					$nav->setUl(strtolower($link->link_name));
					$nav->buildNav();
					$this->sublinks_array[$index] = $nav;
				}
			}
		}
	}

	/**
	 * Puts all the link pieces together in the &lt;a&gt; tag
	 *
	 * @param $link_data Object
	 * @return String
	 */
	private function buildLink($link_data) {
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
                case "sort_order":
                case "active_date":
                case "category":
                case "term_date":
                    break;
				default:
					if( !empty($value) ) {
						$attributes[] = $index.'="'.$value.'"';
					}
			}
		}

		if(!$link_data->link_url && (!isset($link_tag) || count($link_tag) < 1) ) {
			$link = void_link($link_data->link_name, $attributes);
		}else if( isset($link_tag) && count($link_tag) > 0 ) {
			$link = link_tag($link_tag);
		} else {
			$link = anchor($link_data->link_url, $link_data->link_name, $attributes);
		}

		return $link;
	}

	/**
	 * Creates the &lt;ul&gt;
	 *
	 * @return void
	 */
	private function generateUl() {
		if(is_array($this->links_array)) {
            $category = str_replace("|", "_", $this->category);
			foreach($this->links_array as $link_id => $link) {
				$this->ul .= "<li class='{$category}'>".$link;

				$this->generateSubUl($link_id);

				$this->ul .= "</li>";
			}
		}

		$this->ul .= "</ul>";
	}

	/**
	 * This method stores sublinks
	 *
	 * @param link_id mixed
	 * @return void
	 */
	private function generateSubUl($link_id) {
		if( isset($this->sublinks_array[$link_id]) ) {
			$this->ul .= $this->sublinks_array[$link_id]->getUl();
		}
	}

	public function setCategory($category) {
		$this->category = $category;
	}

	public function setUl($class) {
		$this->ul = "<ul class='".$class."'>";
	}

	public function getUl() {
		return $this->ul;
	}
}