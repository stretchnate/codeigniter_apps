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
                <h1>
                    <a href="/"><img src="/images/logo.png" alt="Logo" height="60px" /></a>
                    <img src="/images/header_tx.png" alt="Event Column, create and find events" />
                </h1>
                <div id="search_menu_container">
                    <?=$this->mini_search->renderForm(); ?>
                    <div class="menu">
                        <div class="menu_dropdown">
                            <ul>
                                <li><a href="/user/profile">Profile</a></li>
                                <li><a href="/user/events">My Events</a></li>
                                <li><a href="/search/advanced">Advanced Search</a></li>
                                <li><a href="/logout">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        protected function generateView() {

        }

    }
