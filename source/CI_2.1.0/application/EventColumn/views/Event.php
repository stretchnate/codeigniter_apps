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
		<div id="event-map">
			<div id='event-form'>
				<?php
				$this->event_form->renderForm();
				?>
			</div>

		</div>
		<?php
	}

	public function setEventForm(Form $event_form) {
		$this->event_form = $event_form;
	}

}
?>
