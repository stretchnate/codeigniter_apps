<?php
	/**
	 * This interface defines how all List libraries should be implemented.
	 *
	 * @author stretch
	 * @since 1.0
	 */
	interface DataList {
		/**
		 * builds an ordered list <ol>, can include links
		 */
		public function buildOL($include_links);

		/**
		 * builds an unordered list <ul>, can include links
		 */
		public function buildUL($include_links);

		/**
		 * builds a Form_Field_Select or Form_Field_Select_Multiselect object libraries
		 */
		public function buildSelectObject($label, $name, $default_selected, $error_label, $multi);

		/**
		 * builds a php array using the category_id as the index and category_name as the value
		 */
		public function buildPHPArray();

		/**
		 * set the href base value for links in buildOL() and buildUL()
		 */
		public function setHrefBase($href_base);

		/**
		 * get the <ol>
		 */
		public function getOL();

		/**
		 * returns the <ul>
		 */
		public function getUL();

		/**
		 * returns the Form_Field_Select or Form_Field_Select_Multiselect object
		 */
		public function getSelectObject();

		/**
		 * renders the output of the Form_Field_Select or Form_Field_Select_Multiselect object
		 * and returns the string
		 */
		public function getSelect();

		/**
		 * returns the php_array
		 */
		public function getPHPArray();
	}
?>
