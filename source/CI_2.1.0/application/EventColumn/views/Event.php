<?php
require_once('baseVW.php');

class EventVW extends BaseVW {

	public function __construct(&$CI) {
		parent::__construct($CI);
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
			<div id='event-form'></div>
			<div id='map_canvas'>

			</div>
		</div>
		<?php
	}

}
?>
