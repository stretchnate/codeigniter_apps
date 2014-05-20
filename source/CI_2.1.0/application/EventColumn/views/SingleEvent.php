<?php
	require_once ('topViews/searchHeaderVW.php');

	/**
	 * Description of Map
	 *
	 * @author stretch
	 */
	class SingleEventVW extends searchHeaderVW {

		protected $event_iterator;

		public function __construct() {
			parent::__construct();
		}

		public function generateView() {
			?>
			<div id="event_map">
				<div id='event_form'>
					<?php
					if(isset($this->event_iterator) && $this->event_iterator instanceof EventIterator) {
					?>
						<div id='event_list'>
							<h2>Event Details</h2>
					<?php
						while($this->event_iterator->valid()) {
							echo $this->fetchEventDetails();

							$this->event_iterator->next();
						}
					?>
						</div>
						<div id="flyer_map_container">
							<div class="flyer"></div>
							<div class="map">
								<script type="text/javascript">
									var map;
									var markers = [];
									var lat_long = new Array();

									function initialize() {
										var coordinates = getCoordinates();
										if(coordinates.length > 0) {
											var map_canvas = document.getElementById('map_canvas');
											var map_options = {
													center:  new google.maps.LatLng(coordinates[0]['lattitude'], coordinates[0]['longitude']),
													zoom: 12,
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
					} else {
						echo "There are no events matching your criteria.";
					}
				?>
				</div>

			</div>
			<?php
		}

		protected function fetchEventDetails() {
			$html = '<div>';
			$html .= '<span class="label">Title:</span>';
			$html .= '<span class="details">'.$this->event_iterator->getEventName()."</span>";
			$html .= '</div>';

			$html .= '<div>';
			$html .= '<span class="label">Start Date:</span>';
			$html .= '<span class="details">'.$this->fetchEventDate('start')."</span>";
			$html .= '</div>';

			$html .= '<div>';
			$html .= '<span class="label">End Date:</span>';
			$html .= '<span class="details">'.$this->fetchEventDate('end')."</span>";
			$html .= '</div>';

			foreach($this->event_iterator->getEventLocations() as $location) {
				$html .= $this->fetchLocationDetails($location);
			}

			$html .= '<div>';
			$html .= '<span class="label">Description:</span>';
			$html .= '<span class="details">'.$this->event_iterator->getEventDescription()."</span>";
			$html .= '</div>';

			$html .= $this->getEventPageLink(EventMask::maskEventId($this->event_iterator->getEventId()));

			return $html;
		}

		protected function getEventPageLink($event_id) {
			$html = "<br />";
			$html .= "<a href='/map/event_details/".$event_id."'>event page</a>";

			return $html;
		}

		protected function fetchEventDate($type) {
			switch($type) {
				case 'end':
					$date = new DateTime($this->event_iterator->getEventEnd());
					break;

				case 'start':
				default:
					$date = new DateTime($this->event_iterator->getEventStart());
					break;
			}

			return $date->format("m/d/Y g:i a");
		}

		protected function fetchLocationDetails(&$location) {
			$html = '<div>';
			$html .= '<span class="label">Venue:</span>';
			$html .= '<span class="details">'.$location->getEventLocation()."</span>";
			$html .= '</div>';

			$html .= '<div>';
			$html .= '<span class="label">Address:</span>';
			$html .= '<span class="details">'.$location->getLocationAddress()."</span>";
			$html .= '</div>';

			$html .= '<div>';
			$html .= '<span class="label">City:</span>';
			$html .= '<span class="details">'.$location->getLocationCity()."</span>";
			$html .= '</div>';

			$html .= '<div>';
			$html .= '<span class="label">State:</span>';
			$html .= '<span class="details">'.$location->getLocationState()."</span>";
			$html .= '</div>';

			$html .= '<div>';
			$html .= '<span class="label">Zip:</span>';
			$html .= '<span class="details">'.$location->getLocationZip()."</span>";
			$html .= '</div>';

			return $html;
		}

		public function setEventIterator(EventIterator $event_iterator) {
			$this->event_iterator = $event_iterator;
		}
	}

?>
