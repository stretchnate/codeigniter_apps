<?php
    require_once('topViews/searchHeaderVW.php');

    /**
     * This is the home page view
     *
     * @author stretch
     */
    class HomeVW extends searchHeaderVW {

        protected $content;

        public function __construct() {
            parent::__construct();
        }

        protected function generateView() {
            parent::generateView();
            ?>
            <div id="home-content">
                <div id="ec_world">
                    <img src="/images/splash.jpg" alt="create and find events" title="create and find events" />
                </div>
            </div>
            <?php
        }

        public function setContent( $content ) {
            $this->content = $content;
        }

    }
?>
