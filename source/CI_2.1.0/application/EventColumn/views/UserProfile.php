<?php
	require_once('baseVW.php');

	/**
	 * Description of userProfile
	 *
	 * @author stretch
	 */
	class UserProfileVW extends BaseVW {

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
			<?
		}

		public function setForm(Form $form) {
			$this->form = $form;
			return $this;
		}
	}

?>
