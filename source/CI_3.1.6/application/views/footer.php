<?php
	$uri = str_replace("/", "_", $this->uri->uri_string());
    $n = 0;
?>
	</div><!-- end div content -->
	<div id="footer">
        <div class='links'>
            <?php
            $footer_nav = new NavigationUlLIB('footer');
            echo $footer_nav->getUl();
            ?>
        </div>
        <div id="copy">
            &copy;2010-<?php $year = date('Y'); echo $year;?> <?=COMPANY_NAME;?>
            <!--<span class="version">v3.2</span>-->
            <!--<a href="/blackjack/blackjack/" target="_blank">Play Blackjack</a>-->
        </div>
	</div>
    <?= showAd(AdFactory::AD_AUTO); ?>
    </div><!-- end div container -->
</body>
</html>

<?php
    function showAd($ad_type) {
        $ad = AdFactory::getAdService();
        $ad->displayAd($ad_type);
    }
?>
