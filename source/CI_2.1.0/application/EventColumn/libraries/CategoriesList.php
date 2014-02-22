<?php
	/**
	 * Description of CategoriesList
	 *
	 * @author stretch
	 */
	class CategoriesList extends N8_Error implements DataList {

		private $ci;
		private $category_dm_array = array();
		private $href_base;

		private $ul;
		private $ol;
		private $select;
		private $php_array;

		public function __construct() {
			parent::__construct();
			$this->ci =& get_instance();
		}

		/**
		 * loads all event categories into $this->category_dm_array
		 *
		 * @return void
		 * @since  1.0
		 */
		public function fetchCategories() {
			$query = $this->ci->db->select('category_id')
					->where('parent_category_id = "" OR parent_category_id IS NULL')
					->get('EVENT_CATEGORIES');

			$this->loadResults($query);
		}

		/**
		 * loads all event categories from a specified zip into $this->category_dm_array
		 *
		 * @return void
		 * @since  1.0
		 */
		public function fetchCategoriesByZip($zip) {
			$query = $this->ci->db->select('DISTINCT e.event_category')
					->from('EVENTS e')
					->join('EVENT_LOCATIONS el', 'el.event_id = e.event_id', 'inner')
					->join('EVENT_CATEGORIES ec', 'ec.category_id = e.event_category', 'inner')
					->where('(ec.parent_category_id = "" OR ec.parent_category_id IS NULL)')
					->where('el.zip = '.$zip)
					->get();

			$this->loadResults($query);
		}

		/**
		 * loads the results of fetchCategories*() into $this->category_dm_array
		 *
		 * @param  type $query
		 * @return void
		 * @since  1.0
		 */
		private function loadResults($query) {
			foreach($query->result() as $row) {
				$category_dm = new EventModel_EventCategoriesDM();
				$category_dm->load($row->category_id);
				$this->category_dm_array[] = $category_dm;
			}
		}

		/**
		 * builds an ordered list <ol>, can include links
		 *
		 * @param  bool $include_links
		 * @return void
		 * @since  1.0
		 */
		public function buildOL($include_links = false) {
			ob_start();
?>
			<ol>
<?
				foreach($this->category_dm_array as $category_dm) {
?>
				<li>
<?
				if($include_links !== false) {
?>
					<a href="<?=$this->href_base . '/' . $category_dm->getCategoryId(); ?>">
<?
				}

					echo $category_dm->getCategoryName();

				if($include_links !== false) {
?>
					</a>
<?
				}
?>
				</li>
<?
				}
?>
			</ol>
<?
			$this->ol = ob_get_contents();
			ob_end_clean();
		}

		/**
		 * builds an unordered list <ul>, can include links
		 *
		 * @param  bool $include_links
		 * @return void
		 * @since  1.0
		 */
		public function buildUL($include_links = false) {
			ob_start();
?>
			<ul>
<?
				foreach($this->category_dm_array as $category_dm) {
?>
				<li>
<?
				if($include_links !== false) {
?>
					<a href="<?=$this->href_base . '/' . $category_dm->getCategoryId(); ?>">
<?
				}

					echo $category_dm->getCategoryName();

				if($include_links !== false) {
?>
					</a>
<?
				}
?>
				</li>
<?
				}
?>
			</ul>
<?
			$this->ul = ob_get_contents();
			ob_end_clean();
		}

		/**
		 * builds a Form_Field_Select or Form_Field_Select_Multiselect object libraries
		 *
		 * @param  string  $label
		 * @param  string  $name
		 * @param  mixed   $default_selected (string or array depending on $multi)
		 * @param  array   $error_label_array ('class' => 'error', 'id' => '', 'content' => '')
		 * @param  boolean $multi
		 * @return void
		 * @since  1.0
		 */
		public function buildSelectObject($label, $name, $default_selected = null, $error_label_array = array(), $multi = false) {
			if(empty($error_label_array)) {
				$error_label_array = array('class' => 'error', 'id' => '', 'content' => '');
			}

			if($multi !== false) {
				$this->select = new Form_Field_Select_MultiSelect();
			} else {
				$this->select = new Form_Field_Select();
			}

			$this->select->setLabel($label);
			$this->select->setName($name);

			foreach($this->category_dm_array as $category_dm) {
				$this->select->addOption($category_dm->getCategoryId(), $category_dm->getCategoryName());
			}

			$this->select->setSelectedOption($default_selected);

			$class   = isset($error_label_array['class'])   ? $error_label_array['class']   : null;
			$id      = isset($error_label_array['id'])      ? $error_label_array['id']      : null;
			$content = isset($error_label_array['content']) ? $error_label_array['content'] : null;

			$this->select->addErrorLabel($class, $id, $content);
		}

		/**
		 * builds a php array using the category_id as the index and category_name as the value
		 *
		 * @return void
		 * @since  1.0
		 */
		public function buildPHPArray() {
			foreach($this->category_dm_array as $category_dm) {
				$this->php_array[$category_dm->getCategoryId()] = $category_dm->getCategoryName();
			}
		}

		/**
		 * set the href base value for links in buildOL() and buildUL()
		 *
		 * @param  string $href_base
		 * @return \CategoriesList
		 * @since  1.0
		 */
		public function setHrefBase($href_base) {
			$this->href_base = $href_base;
			return $this;
		}

		/**
		 * get the <ol>
		 *
		 * @return string
		 * @since  1.0
		 */
		public function getOL() {
			return $this->ol;
		}

		/**
		 * returns the <ul>
		 *
		 * @return string
		 * @since  1.0
		 */
		public function getUL() {
			return $this->ul;
		}

		/**
		 * returns the Form_Field_Select or Form_Field_Select_Multiselect object
		 *
		 * @return object
		 * @since  1.0
		 */
		public function getSelectObject() {
			return $this->select;
		}

		/**
		 * renders the output of the Form_Field_Select or Form_Field_Select_Multiselect object
		 * and returns the string
		 *
		 * @return string
		 * @since  1.0
		 */
		public function getSelect() {
			ob_start();
			$this->select->generateField();
			$select = ob_get_contents();
			ob_end_clean();

			return $select;
		}

		/**
		 * returns the php_array
		 *
		 * @return array
		 * @since  1.0
		 */
		public function getPHPArray() {
			return $this->php_array;
		}
	}

?>
