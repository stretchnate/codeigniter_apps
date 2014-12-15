<?php
    /**
     * renders the google adsense ads
     *
     * @author stretch
     */
    class AdFactory_Adsense implements AdFactory_AdFactoryInterface {

        public function __construct() {}

        public function displayAd($ad_type) {
            if(isLive()) {
                switch($ad_type) {
                    case AdFactory::AD_MEDIUM_RECTANGLE:
                        $this->displayMediumRectangle();
                        break;
                    case AdFactory::AD_AUTO:
                    default:
                        $this->displayAuto();
                }
            } else {
                echo "<div class='google_ad'>google adsense ad will go here</div>";
            }
        }

        /**
         * displays an auto sizing ad
         */
        private function displayAuto() {
        ?>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                 <!--Category - 3 (stretchnate.com)-->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6403299303438002"
                     data-ad-slot="2129536574"
                     data-ad-format="auto"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        <?php
        }

        /**
         * displays a 300 x 250 ad
         */
        private function displayMediumRectangle() {
        ?>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- money_300_x_250 -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:250px"
                     data-ad-client="ca-pub-6403299303438002"
                     data-ad-slot="3569243779"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        <?php
        }
    }
