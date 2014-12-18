<?php
	require_once ('topViews/searchHeaderVW.php');
	/**
	 * Description of Search
	 *
	 * @author stretch
	 */
	class SearchVW extends searchHeaderVW {

		protected $search_form;

		public function __construct() {
			parent::__construct();
		}

		public function generateView() {
			?>
			<div id="pane_container">
				<div id='left_pane' class='columns'>
					<?php
					$this->search_form->renderForm();
					?>
				</div>
                <div id="right_pane" class='columns globe_logo_bg'></div>
			</div>
			<?php
		}

		public function setSearchForm(Form $search_form) {
			$this->search_form = $search_form;
		}
	}
?>
