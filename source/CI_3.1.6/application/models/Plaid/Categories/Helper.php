<?php
/**
 * Created by PhpStorm.
 * User: stret
 * Date: 5/15/2018
 * Time: 7:37 PM
 */

namespace Plaid\Categories;

use Plaid\Categories;

/**
 * Class Helper
 *
 * @package Plaid\Categories
 */
class Helper extends \CI_Model {

    /**
     * @param Categories $categories
     * @return array
     */
    public function createCategoriesArray(Categories $categories) {
        $top_level_array = [];
        foreach($categories->getCategories() as $category) {
            $key = substr($category->getCategoryId(), 0, 5);
            if(array_key_exists($key, $top_level_array)) {
                continue;
            }

            $category = isset($category->getHierarchy()[1])
                ? $category->getHierarchy()[1] . $category->getHierarchy()[0]
                : $category->getHierarchy()[0];

            $top_level_array[$key] = $category;
        }

        return $top_level_array;
    }
}