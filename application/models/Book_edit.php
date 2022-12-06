<?php
class Book_edit extends N8_Model {
		
	function __construct(){
            parent::__construct();
	}
	
	function bookOffOn($id){
		$this->db->select('active');
		$query = $this->db->get_where('booksummary',array('bookId' => $id));
		$result = $query->result();
		
		if(!$result){
			$data['message'] = 'There was an error ';
			$this->load->view('error_view', $data);
		} else {
			if($result[0]->active == 0){
				$info = array('active' => 1);
				$this->db->where('bookId',$id);
				$query = $this->db->update('booksummary',$info);
				if(!$query){
					$data['success'] = false;
					$data['message'] = 'ERROR: Update failed.';
					$data['bookId'] = $id;
					return $data;
				} else {
					$data['success'] = true;
					return $data;
				}
			} else if ($result[0]->active == 1){
				$info = array('active' => 0);
				$this->db->where('bookId',$id);
				$query = $this->db->update('booksummary',$info);
			if(!$query){
					$data['success'] = false;
					$data['message'] = 'ERROR: Update failed.';
					$data['bookId'] = $id;
					return $data;
				} else {
					$data['success'] = true;
					return $data;
				}
			} else {
				$data['message'] = 'ERROR: There was an Invalid value found in the "active" field, please notify the webmaster of the gross error.';
				$data['bookId'] = $id;
				return $data;
			}
			unset($info);
		}
	}
	
	function save($id){
		$info = array('bookAmtNec' => $this->input->post('amtNec'),
					'bookName' => $this->input->post('name'),
					'due_day' => $this->input->post('dueDay'));

		$this->db->where('bookId',$id);
		$query = $this->db->update('booksummary',$info);
		if(!$query) {
			$data['success'] = false;
			$data['message'] = 'Error updating account';
			return $data;
		} else {
			$data['success'] = true;
			return $data;
		}
	}
}
?>
