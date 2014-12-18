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
			<div id="pane_container">
				<div id="left_pane" class="columns">
					<?php
					$this->form->renderForm();
					?>
				</div>
                <div id="right_pane" class="columns">
                    <div class="globe_logo_bg">&nbsp;</div>
                </div>
                <div class="clear">&nbsp;</div>
			</div>
			<?php
		}

		public function setForm(Form $form) {
			$this->form = $form;
			return $this;
		}
	}

?>
