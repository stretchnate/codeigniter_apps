<?php
class Utils extends N8_Model {

	function Utils(){
		parent::__construct();
	}

	function getLinks($category) {
		$this->db->order_by('sort_order', 'asc');
		$data = $this->db->get_where('links', array('category' => $category, 'term_date' => NULL), NULL);
		return $data->result();
	}
}
?>
