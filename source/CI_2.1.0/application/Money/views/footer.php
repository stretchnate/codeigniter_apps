<?php
	$uri = str_replace("/", "_", $this->uri->uri_string());
    $n = 0;
?>
	</div><!-- end div content -->
	<div id="post-it-notes">
		<?php
		if( isset($notes) && count($notes) > 0) {
			foreach($notes as $note) {
                $n++;
                if($n == 2) {
                    showAd(AdFactory::AD_AUTO);
                }
        ?>
			<div class="post-it">
				<?php echo  $note->note_text; ?>
				<br />
				<a href="/notes/showNoteForm/<?php echo $note->note_id;?>">Edit Note</a>
				<br />
				<a href="/notes/deleteNote/<?php echo $note->note_id;?>/<?php echo $uri?>">Delete Note</a>
			</div>
			<?php
                $n++;
			}
		}

        if($n < 2 || $n > 4) {
            showAd(AdFactory::AD_MEDIUM_RECTANGLE);
        }
        ?>
	</div>
	<div class="clear">&nbsp;</div>
	<div id="footer">
        <div class='links'>
            <?php
            $footer_nav = new NavigationUlLIB('footer');
            echo $footer_nav->getUl();
            ?>
        </div>
        <div id="copy">
            &copy;2010-<?php $year = date('Y'); echo $year;?> stretchnate.com
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
