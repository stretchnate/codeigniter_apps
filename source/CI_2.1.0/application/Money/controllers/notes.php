<?
class Notes extends N8_Controller {

	function Welcome() {
		parent::__construct();
		$this->load->library('utilities');
		$this->load->helper('html');
	}

	function showNoteForm($note_id = null, $result = array(), $category_id = null) {
		$this->auth->restrict();
		$this->load->model('notes_model', 'NM', TRUE);
		$this->load->model('accounts', 'ACCT', TRUE);

		$props['title'] = "Add Edit Notes";
		$props['links'] = $this->utilities->createLinks('main_nav');

		$data = array();
		if($note_id && count($result) < 1) {
			$data['note'] = $this->NM->getNote($note_id, $this->session->userdata('user_id'));
		}

		if($result) {
			if( !$result['note_id'] ) {
				$data['result']->message = $result['message']."<br />I was unable to verify if the note was added successfully, please check for it on the main page.";
			} else {
				$data['result']->message = $result['message'];
				$data['result']->success = $result['success'];
			}
		}

		$data['accounts'] = $this->ACCT->getAccountsAndDistributableCategories($this->session->userdata('user_id'));
		$data['selected_category'] = $category_id;

		$this->load->view('header',$props);
		$this->load->view('notes/addEditNotesVW',$data);
		$this->load->view('footer');
	}

	function addEditNote($note_id = null) {
		$this->auth->restrict();
		$this->load->model('notes_model', 'NM', TRUE);

		$note_text = trim($this->input->post('note_text'));
		$note_priority = trim($this->input->post('note_priority'));
		$category_id = trim($this->input->post('account_id'));

		$update = $this->NM->addEditNote($this->session->userdata('user_id'), $note_id, $note_text, $note_priority, $category_id);

		$this->showNoteForm($update['note_id'], $update);
	}

	function addNewNote($category_id = null) {
		$this->showNoteForm(null, array(), $category_id);
	}

	function deleteNote($note_id, $uri = null) {
		$this->auth->restrict();

		$this->load->model('notes_model', 'NM', TRUE);
		$this->NM->deleteNote($note_id);

		if($uri) {
			$uri = str_replace("_", "/", $uri);
			redirect($uri);
		}
		else {
			redirect("/");
		}
	}
}
