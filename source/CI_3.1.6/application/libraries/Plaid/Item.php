<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/25/18
 * Time: 7:09 PM
 */

namespace Plaid;


trait Item {

    /**
     * @var object
     */
    private $item;

    /**
     * @return object
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * @param object $item
     */
    protected function setItem($item) {
        $this->item = $item;
    }
}