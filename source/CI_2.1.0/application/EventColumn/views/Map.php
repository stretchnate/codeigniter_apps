<?php
	require_once ('baseVW.php');

	/**
	 * Description of Map
	 *
	 * @author stretch
	 */
	class MapVW extends BaseVW {

		protected $event_iterator;

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
					if(isset($this->event_iterator) && $this->event_iterator instanceof EventIterator) {
						echo "<div id='event-list'>";
						while($this->event_iterator->valid()) {
							echo "<p>";
							echo $this->event_iterator->getEventName() . "<br />";
							echo $this->event_iterator->getEventCategory() . "<br />";
							echo $this->event_iterator->getEventStart() . "<br />";
							echo $this->event_iterator->getEventDescription();
							echo "</p>";

							$this->event_iterator->next();
						}
						echo "</div>";
					} else {
						echo "There are no events matching your criteria.";
					}
					?>
					<div id='map_canvas'></div>
				</div>

			</div>
			<?php
		}

		public function setEventIterator(Form $event_iterator) {
			$this->event_iterator = $event_iterator;
		}
	}

?>
