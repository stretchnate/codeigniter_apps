<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 5/15/2018
 * Time: 4:48 PM
 */

namespace Plaid;

use Plaid\Categories\Category;

class Categories extends Plaid {

    use RequestId;

    /**
     * @var Category[]
     */
    private $categories;

    public function __construct($raw_response) {
        parent::__construct($raw_response);
        $this->loadCategories();
    }

    private function loadCategories() {
        foreach($this->getRawResponse()->categories as $category) {
            $this->categories[] = new Category($category);
        }
    }

    /**
     * @return Category[]
     */
    public function getCategories() {
        return $this->categories;
    }
}