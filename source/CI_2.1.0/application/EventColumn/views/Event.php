<?php
require_once('topViews/searchHeaderVW.php');

class EventVW extends searchHeaderVW {

	protected $map;
	private $event_form;

	public function __construct() {
		parent::__construct();
	}

	public function generateView() {
		?>
		<div id="pane_container">
			<div id='left_pane' class='columns'>
				<?php
				$this->event_form->renderForm();
				?>
			</div>
			<div id='right_pane' class='columns'>

			</div>
			<div class='clear'>&nbsp;</div>
		</div>
		<?php
	}

	public function setEventForm(Form $event_form) {
		$this->event_form = $event_form;
	}

}
?>
