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

			<div id="event-map">
				<div id='event-form'>
					<?php
					if(isset($this->event_iterator) && $this->event_iterator instanceof EventIterator) {
						echo "<div id='event-list'>";
						while($this->event_iterator->valid()) {
							echo "<p>";
							echo $this->event_iterator->getEventName() . "<br />";
							echo $this->event_iterator->getEventCategory() . "<br />";

							$start_date = new DateTime($this->event_iterator->getEventStart());
							echo $start_date->format("m/d/Y g:i a") . "<br />";

							echo $this->event_iterator->getEventDescription() . "<br />";
							foreach($this->event_iterator->getEventLocations() as $location) {
								$address = $location->getEventLocation() . "<br />";
								$address .= $location->getLocationAddress() . "<br />";
								$address .= $location->getLocationCity() . ", ";
								$address .= $location->getLocationState() . " ";
								$address .= $location->getLocationZip();
								echo $address;
							}
							echo "</p>";

							$this->event_iterator->next();
						}
						echo "</div>";
					} else {
						echo "There are no events matching your criteria.";
					}
					?>
					<script type="text/javascript">
						var map;
						var markers = [];
						var lat_long = new Array();



						function initialize() {
							var coordinates = getCoordinates();
							if(coordinates.length > 0) {
								var map_canvas = document.getElementById('map_canvas');
								var map_options = {
										//center: new google.maps.LatLng(44.5403, -78.5463),
										center:  new google.maps.LatLng(coordinates[0]['lattitude'], coordinates[0]['longitude']),
										zoom: 14,
										mapTypeId: google.maps.MapTypeId.ROADMAP
								};

								map = new google.maps.Map(map_canvas, map_options);

								for(var j = 0; j < coordinates.length; j++) {
									addMarker(new google.maps.LatLng(coordinates[j]['lattitude'], coordinates[j]['longitude']), coordinates[j]['title']);
								}
							}
						}

						function addMarker(lat_lng, marker_title) {
						  markers.push(new google.maps.Marker({
							position: lat_lng,
							map: map,
							title: marker_title
							//icon:[enter image location here]
						  }));
						}

						function getCoordinates() {
							var coordinates = new Array();

							<?
							if(isset($this->event_iterator) && $this->event_iterator instanceof EventIterator) {
								$this->event_iterator->rewind();
								$i = 0;
								while($this->event_iterator->valid()) {
									$locations = $this->event_iterator->getEventLocations();

									foreach($locations as $location) {
										$lat_long = explode(", ", $location->getLatLong());

										echo "coordinates[$i] = new Array();\n\t\t\t\t\t\t";
										echo "coordinates[$i]['lattitude'] = {$lat_long[0]};\n\t\t\t\t\t\t\t\t";
										echo "coordinates[$i]['longitude'] = {$lat_long[1]};\n\t\t\t\t\t\t\t";
										echo 'coordinates['.$i.']["title"] = "' . $this->event_iterator->getEventName() . '";';
										$i++;
									}

									$this->event_iterator->next();
								}
							}
							?>

							return coordinates;
						}

						$(document).ready(function() {
							initialize();
						});
					</script>
					<div id='map_canvas'></div>
				</div>

			</div>
			<?php
		}

		public function setEventIterator(EventIterator $event_iterator) {
			$this->event_iterator = $event_iterator;
		}
	}

?>
