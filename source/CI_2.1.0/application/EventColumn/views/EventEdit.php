<?php
    require_once('topViews/searchHeaderVW.php');

    /**
     * Description of EventEdit
     *
     * @author stretch
     */
    class EventEditVW extends searchHeaderVW {
        private $event_form;
        private $event_image;
        private $event_name;

        public function __construct() {
            parent::__construct();
        }

        public function generateView() {
            ?>
            <div id="event_map">
                <div id='event_list' class='columns'>
                    <?php
                    $this->event_form->renderForm();
                    ?>
                </div>
                <div id='flyer_map_container' class='columns'>
                    <div class="flyer">
                        <img src="<?=site_url($this->event_image);?>" height="350" alt="<?=$this->event_name;?> flyer" />
                    </div>
                    <div class="single_map">
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
                                var loop        = true;
                                var i           = 0;
                                var details     = null;

                                while(loop === true) {
                                    if($('input[name="event_details_locations\['+i+'\]\[lat_long\]"]').val()) {
                                        details = $('input[name="event_details_locations\['+i+'\]\[lat_long\]"]').val().split(', ');
                                        coordinates[i] = new Array();
                                        coordinates[i]['lattitude'] = details[0];
                                        coordinates[i]['longitude'] = details[1];
                                        coordinates[i]['title'] = $('input[name=event_name]').val();
                                    } else {
                                        loop = false;
                                    }
                                    i++;
                                }

                                return coordinates;
                            }

                            $(document).ready(function() {
                                initialize();
                            });


                        </script>
                        <div id='map_canvas'></div>
                    </div>
                </div>
                <div class='clear'>&nbsp;</div>
            </div>
            <?php
        }

        public function setEventForm(Form $event_form) {
            $this->event_form = $event_form;
            return $this;
        }

        public function setEventImage($image) {
            $this->event_image = $image;
            return $this;
        }

        public function setEventName($name) {
            $this->event_name = $name;
            return $this;
        }
    }
