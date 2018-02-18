<?php
class Notes_model extends N8_Model {
		
	function __construct(){
            parent::__construct();
	}

	function getAllNotes($user_id, $account_id = 0) { //@TODO integrate adsense into the results before returning them.
		$where = "user_id = {$user_id} AND active = 1";
		if($account_id > 0) {
			$where .= " AND (account_id = 0 OR account_id = {$account_id})";
		} else {
			$where .= " AND account_id = {$account_id}";
		}
		$query = $this->db->select()
						->from("notes")
						->where( $where )
						->order_by("note_priority")
						->get();
		return $query->result();
	}

	function getNote($note_id, $user_id) {
		$query = $this->db->select()
						->from("notes")
						->where( array("user_id" => $user_id, "note_id" => $note_id) )
						->get();
		return $query->row();
	}

	function addEditNote($user_id, $note_id = null, $note_text = null, $note_priority = null, $account_id = 0) {
		if($note_id) {
			$action = $this->editNote($note_id, $note_text, $note_priority, $account_id);
		} else {
			$action = $this->addNote($user_id, $note_text, $note_priority, $account_id);
		}
		return $action;
	}

	function editNote($note_id, $note_text, $note_priority, $account_id) {
		$result['note_id'] = $note_id;
		$result['success'] = true;
		$result['message'] = "Note successfully updated";

		$data = array(
               'note_text' => $note_text,
               'note_priority' => $note_priority,
			   'account_id' => $account_id
            );

		$sql = $this->db->where('note_id', $note_id)
						->update('notes', $data);

		if( !$sql ) {
			$result['success'] = false;
			$result['message'] = "failed to update note, please try again";
		}

		return $result;
	}

	function addNote($user_id, $note_text, $note_priority, $account_id) {
		$result['success'] = true;
		$result['message'] = "Note successfully added";

		$data = array('user_id' => $user_id, 'note_text' => $note_text, 'note_priority' => $note_priority, 'account_id' => $account_id, 'active' => 1);
		$query = $this->db->insert('notes', $data);

		if( !$query ) {
			$result['success'] = false;
			$result['message'] = "failed to add note, please try again";
		} else {
			$sql = $this->db->select('note_id')
							->from('notes')
							->where(array('user_id' => $user_id, 'note_text' => $note_text, 'account_id' => $account_id, 'active' => 1))
							->get();

			$note = $sql->row();
			$result['note_id'] = $note->note_id;
		}

		return $result;
	}

	function deleteNote($note_id) {
		$data = array('active' => 0);
		$this->db->where('note_id', $note_id)
				->update('notes', $data);
	}
}
?>
