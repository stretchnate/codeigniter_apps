<?php
	require_once('topViews/searchHeaderVW.php');

	/**
	 * Description of userProfile
	 *
	 * @author stretch
	 */
	class UserProfileVW extends searchHeaderVW {

		protected $form;

		public function __construct() {
			parent::__construct();
		}

		public function generateView() {
			?>
			<div id="event-map">
				<div id='event-form'>
					<?php
					$this->form->renderForm();
					?>
				</div>

			</div>
			<?php
		}

		public function setForm(Form $form) {
			$this->form = $form;
			return $this;
		}
	}

?>
