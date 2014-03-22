<?php
	require_once('baseVW.php');

	/**
	 * Description of searchHeaderVW
	 *
	 * @author stretch
	 */
	class searchHeaderVW extends BaseVW {

		protected $mini_search;

		//put your code here
		public function __construct() {
			parent::__construct();
			$this->mini_search = new Plugins_MiniSearch();
		}

		/**
		 * generate the header of the page.
		 */
		public function generateHeader() {
			?>
			<div id="header">
				<h1><a href="/"><img src="/images/header_tx.png" alt="Event Column, create and find events" /></a></h1>
				<span class="welcome">
					Welcome
					<?php
					if( $this->username !== 'Guest' ) {
						?>
						<a href="/userProfile"><?=$this->username; ?></span> |
						<a href="/login/logout">Logout</a>
						<?php
					} else {
						?>
						<?=$this->username; ?> |
						<a href="/login">Login/Register</a>
						<?php
					}
					?>
				</span>
				<?= $this->mini_search->renderForm(); ?>
			</div>
			<?php
		}

		protected function generateView() {}
	}
