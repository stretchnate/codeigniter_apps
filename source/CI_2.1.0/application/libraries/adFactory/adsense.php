<?php
    /**
     * renders the google adsense ads
     *
     * @author stretch
     */
    class AdFactory_Adsense implements AdFactory_AdFactoryInterface {

        public function __construct() {}

        public function displayAd() {
            if(isLive()) {
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
            } else {
                echo "<div class='google_ad'>google adsense ad will go here</div>";
            }
        }
    }
