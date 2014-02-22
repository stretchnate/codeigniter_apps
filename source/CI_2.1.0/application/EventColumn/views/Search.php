<?php
	require_once ('baseVW.php');
	/**
	 * Description of Search
	 *
	 * @author stretch
	 */
	class SearchVW extends BaseVW {

		protected $search_form;

		public function __construct() {
			parent::__construct();
		}

		public function generateView() {
			?>
			<div id="event-map">
				<div id='event-form'>
					<?php
					$this->search_form->renderForm();
					?>
				</div>

			</div>
			<?php
		}

		public function setSearchForm(Form $search_form) {
			$this->search_form = $search_form;
		}
	}

?>
