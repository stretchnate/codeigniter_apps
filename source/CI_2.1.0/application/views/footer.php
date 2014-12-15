<?php
	$uri = str_replace("/", "_", $this->uri->uri_string());
    $n = 0;
    $ads = 0;
?>
	</div><!-- end div content -->
	<div id="post-it-notes">
		<?php
		if( isset($notes) && count($notes) > 0) {
			foreach($notes as $note) {
                if($n % 2 == 0) {
                    showAd();
                    $ads++;
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

        while($ads < 3) {
            showAd();
            $ads++;
        }
        ?>
	</div>
	<div class="clear">&nbsp;</div>
</div><!-- end div container -->
	<div id="footer" class="border">
		<div id="copy"><a href="/blackjack/blackjack/" target="_blank">Play Blackjack</a>&nbsp;&nbsp;<span class="version">v 3.1</span>&nbsp;&copy; <?php $year = date('Y'); echo $year;?> Me.</div>
	</div>
</body>
</html>

<?php
    function showAd() {
        $ad = AdFactory::getAdService();
        $ad->displayAd(AdFactory::AD_MEDIUM_RECTANGLE);
    }
?>
