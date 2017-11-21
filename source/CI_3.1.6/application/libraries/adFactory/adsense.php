<?php
    /**
     * renders the google adsense ads
     *
     * @author stretch
     */
    class AdFactory_Adsense implements AdFactory_AdFactoryInterface {

        public function __construct() {}

        public function displayAd($ad_type) {
            switch($ad_type) {
                case AdFactory::AD_MEDIUM_RECTANGLE:
                    $this->displayMediumRectangle();
                    break;
                case AdFactory::AD_AUTO_TEXT_ONLY:
                    $this->displayTextOnlyAuto();
                    break;
                case AdFactory::AD_WIDE_SKYSCRAPER:
                    $this->displayWideSkyscraper();
                    break;
                case AdFactory::AD_AUTO:
                default:
                    $this->displayAuto();
            }
        }

        /**
         * displays a wide skyscraper
         */
        private function displayWideSkyscraper() {
        ?>
            <div class='google_ad adblock'>
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- money_wide_skyscraper -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:160px;height:600px"
                         data-ad-client="ca-pub-6403299303438002"
                         data-ad-slot="8409742571"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        <?php
        }

        /**
         * displays an auto sizing text only ad
         */
        private function displayTextOnlyAuto() {
        ?>
            <div class='google_ad adblock'>
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- money_text_only_auto -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:728px;height:90px"
                         data-ad-client="ca-pub-6403299303438002"
                         data-ad-slot="2782011379"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        <?php
        }

        /**
         * displays an auto sizing ad
         */
        public function displayAuto() {
            ?>
                <div class='google_ad adblock'>
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <!-- money_auto -->
                        <ins class="adsbygoogle"
                             style="display:inline-block;width:728px;height:90px"
                             data-ad-client="ca-pub-6403299303438002"
                             data-ad-slot="2502809774"></ins>
                        <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            <?php
        }

        /**
         * displays a 300 x 250 ad
         */
        private function displayMediumRectangle() {
        ?>
            <div class='google_ad adblock'>
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- money_300_x_250 -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:300px;height:250px"
                         data-ad-client="ca-pub-6403299303438002"
                         data-ad-slot="3569243779"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
        <?php
        }
    }
