<?php
require_once('baseVW.php');

class RegisterVW extends BaseVW {

	protected $map;
	private $register_form;

	public function __construct() {
		parent::__construct();
	}

	public function generateView() {
		?>
		<div id="side-nav">
			<ul>
				<li>Appearances</li>
				<li>Art</li>
				<li>Car</li>
				<li>Church</li>
				<li>Club</li>
				<li>Community</li>
				<li>Concerts</li>
				<li>Dance Events</li>
				<li>Festivals</li>
				<li>Not for profit</li>
				<li>Openings</li>
				<li>Parties</li>
				<li>Restaurants</li>
				<li>Sale Events</li>
				<li>Seminars</li>
				<li>Sport Events</li>
				<li>Theatrical Events</li>
				<li>Yard Sales</li>
				<li>Other</li>
			</ul>
		</div>
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
