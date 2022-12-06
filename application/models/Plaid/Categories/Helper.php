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
    public function createCategoriesOptions(Categories $categories) {
        $top_level_array = [];
        foreach($categories->getCategories() as $category) {
            $key = substr($category->getCategoryId(), 0, 5);
            if(array_key_exists($key, $top_level_array)) {
                continue;
            }

            if(empty($category->getHierarchy()[1])) {
                $okey = ($key - 1) . '.1';
                $top_level_array[$okey] = "</optgroup><optgroup label='".$category->getHierarchy()[0]."'>";
                $top_level_array[$key] = "<option value='$key'>".$category->getHierarchy()[0]."</option>";
            } else {
                $top_level_array[$key] = "<option value='$key'>".$category->getHierarchy()[1]."</option>";
            }
        }

        $top_level_array[$key + 1] = "</optgroup>";

        return $top_level_array;
    }
}