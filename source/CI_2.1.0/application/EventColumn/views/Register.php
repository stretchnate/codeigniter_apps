<?php
require_once('topViews/searchHeaderVW.php');

class RegisterVW extends searchHeaderVW {

	protected $map;
	private $register_form;

	public function __construct() {
		parent::__construct();
	}

	public function generateView() {
		?>
		<div id="event-map">
			<div id='event-form'>
				<?php
				$this->register_form->renderForm();
				?>
			</div>

		</div>
		<?php
	}

	public function setRegisterForm(Form $register_form) {
		$this->register_form = $register_form;
	}

}
?>
