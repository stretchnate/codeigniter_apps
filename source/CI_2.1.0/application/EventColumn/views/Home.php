<?php
	require_once('baseVW.php');

	/**
	 * This is the home page view
	 *
	 * @author stretch
	 */
	class HomeVW extends BaseVW {

		protected $content;

		public function __construct() {
			parent::__construct();
		}

		protected function generateView() { ?>
			<div id="home-content">
				<div id="ec-world">
					<img src="/images/home_globe.png" alt="Event Column World" title="what's going on in your world?" />
				</div>
			</div>
			<?php
		}

		public function setContent($content) {
			$this->content = $content;
		}
	}

?>
