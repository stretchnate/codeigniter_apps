<?php

class EventColumn_EventModel_EventCategoriesDM extends BaseDM {

	private $category_id;
	private $category_name;
	private $parent_category_id;
	protected $child_categories = array();

	/**
	 * class construct method
	 *
	 * @access public
	 * @return Object
	 * @since  1.0
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * loads the event categories from the EVENT_CATEGORIES table.
	 *
	 * @param  int $event_details_id
	 * @access public
	 * @return void
	 * @throws Exception
	 * @since  1.0
	 */
	public function load($id) {
		$query = $this->db->get_where("EVENT_CATEGORIES", array("category_id" => $id));

		$categories = $query->result();

		if (is_array($categories)) {
			$categories = $categories[0];

			$this->category_id = $categories['category_id'];
			$this->category_name = $categories['category_name'];
			$this->parent_category_id = $categories['parent_category_id'];

			$this->loadChildCategories();
		} else {
			throw new Exception("unable to load category for category_id " . $id);
		}
	}

	/**
	 * loads the child categories of the loaded category.
	 *
	 * @param  int $id
	 * @access protected
	 * @return void
	 * @throws Exception
	 * @since  1.0
	 */
	protected function loadChildCategories($id) {
		$query = $this->db->get_where("EVENT_CATEGORIES", array("parent_category_id" => $id));

		$categories = $query->result();

		if (is_array($categories)) {
			$i = 0;
			foreach ($categories as $category) {
				$this->child_categories[$i] = new EventColumn_EventModel_EventCategoriesDM();
				$this->child_categories[$i]->load($category['category_id']);
				$i++;
			}
		} else {
			throw new Exception("unable to load category for category_id " . $id);
		}
	}

	/**
	 * save the event categories to the database.
	 *
	 * @access public
	 * @return mixed
	 * @since  1.0
	 */
	public function save() {
		if ($this->category_id > 0) {
			if (!$this->update()) {
				$this->setError("There was a problem updating the event categories for  " . $this->category_id);
			}
		} else {
			$this->insert();
			$this->insert_id = $this->db->insert_id();
			return $this->insert_id;
		}
	}

	/**
	 * update the EVENT_CATEGORIES table
	 *
	 * @access private
	 * @return boolean
	 * @since  1.0
	 */
	private function update() {
		$sets = array();

		$sets["category_name"] = $this->category_name;
		$sets["parent_category_id"] = $this->parent_category_id;

		$this->db->where("event_details_id", $this->category_id);
		if ($this->db->update("EVENT_CATEGORIES", $sets)) {
			return true;
		}
		return false;
	}

	/**
	 * insert a new EVENT_CATEGORIES row
	 *
	 * @access private
	 * @return unknown
	 * @since  1.0
	 */
	private function insert() {
		$values = array();

		$values["category_name"] = $this->category_name;
		$values["parent_category_id"] = $this->parent_category_id;

		return $this->db->insert("EVENT_DETAILS", $values);
	}

	/**
	 * gets the category_id
	 *
	 * @return int
	 * @since  1.0
	 */
	public function getCategoryId() {
		return $this->category_id;
	}

	/**
	 * gets the category_name
	 *
	 * @return bool
	 * @since  1.0
	 */
	public function getCategoryName() {
		return $this->category_name;
	}

	/**
	 * sets the category_name
	 *
	 * @param  bool
	 * @return Object
	 * @since  1.0
	 */
	public function setCategoryName($category_name) {
		$this->category_name = $category_name;
		return $this;
	}

	/**
	 * gets the parent_category_id
	 *
	 * @return bool
	 * @since  1.0
	 */
	public function getParentCategoryId() {
		return $this->parent_category_id;
	}

	/**
	 * sets the parent_category_id
	 *
	 * @param  bool
	 * @return Object
	 * @since  1.0
	 */
	public function setParentCategoryId($parent_category_id) {
		$this->parent_category_id = $parent_category_id;
		return $this;
	}

	/**
	 * gets the child_categories
	 *
	 * @return String
	 * @since  1.0
	 */
	public function getChildCategories() {
		return $this->child_categories;
	}

	/**
	 * sets the age_range
	 *
	 * @param  String
	 * @return Object
	 * @since  1.0
	 */
	public function setChildCategories(array $child_categories) {
		$this->child_categories = $child_categories;
		return $this;
	}

}

?>
