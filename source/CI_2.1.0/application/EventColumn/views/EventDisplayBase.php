<?php
    require_once('topViews/searchHeaderVW.php');

    /**
     * Description of EventDisplayBase
     *
     * @author stretch
     */
    class EventDisplayBaseVW extends searchHeaderVW {

        protected $event_iterator;

        protected function fetchEventDetails() {
			$html = '<div>';
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

            return $html;
		}

		protected function getEventPageLink($event_id) {
			$html = "<br />";
			$html .= "<a href='/map/event_details/".$event_id."'>event page</a>";

			return $html;
		}

        protected function getEventEditLink($event_id) {
            $html = "<br />";
			$html .= "<a href='/event/edit/".$event_id."'>edit event</a>";

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

			return $date->format("l - F j Y g:ia");
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

            $age_range = str_replace("_", "-", $location->getEventDetailsDM()->getAgeRange());
            $html .= '<div>';
			$html .= '<span class="label">Age Range:</span>';
			$html .= '<span class="details">'.ucwords($age_range)."</span>";
			$html .= '</div>';

            $admission = ucwords($location->getEventDetailsDM()->getAdmission());
            $html .= '<div>';
			$html .= '<span class="label">Admission:</span>';
			$html .= '<span class="details">' . $admission . "</span>";
			$html .= '</div>';

            $html .= '<div>';
			$html .= '<span class="label">Food & Drinks:</span>';
			$html .= '<span class="details">'.
                    ucwords($location->getEventDetailsDM()->getFoodAvailable()).
                    "</span>";
			$html .= '</div>';

            $html .= '<div>';
			$html .= '<span class="label">Smoking:</span>';
			$html .= '<span class="details">'.ucwords($location->getEventDetailsDM()->getSmoking()). '</span>';
			$html .= '</div>';

			return $html;
		}

		public function setEventIterator(EventIterator $event_iterator) {
			$this->event_iterator = $event_iterator;
		}
    }
